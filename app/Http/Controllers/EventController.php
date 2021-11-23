<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\event;
use App\Models\loveone;
use App\Models\Invitation;
use App\Models\careteam;
use App\User;
use DateTime;
use DateInterval;
use App\Notifications\EventNotification;
use Illuminate\Support\Facades\Notification;

use function GuzzleHttp\json_decode;

use App\Http\Traits\NotificationTrait;

class EventController extends Controller
{
    use NotificationTrait;
    //use Notification;

    const EVENTS_TABLE = 'events';

    public function index(Request $request)
    {   
        $is_admin = false;
        $to_day = new DateTime();
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        if(!$loveone){
            return view('errors.not-found');
        }
        
        if(!Auth::user()->permission('carehub',$loveone->id))
        {
            return redirect('/home')->with('err_permisison', 'You don\'t have permission to access CareHub');  
        }
        $careteam = careteam::where('loveone_id', $loveone->id)->get()->keyBy('user_id');
        $membersIds = $careteam->pluck('user_id')->toArray();
        $members = User::whereIn('id', $membersIds)->get();
        $events = event::where('loveone_id', $loveone->id)->where('status','=',1)->get();
        foreach ($members as $key => $member){
            $members[$key]['careteam'] = $careteam[$member->id];
            if(Auth::user()->id == $member->id && $careteam[$member->id]->role == 'admin')
                $is_admin = true;
        }
        $this->areNewNotifications($request->loveone_slug, Auth::user()->id);

        return view('carehub.index',compact('events','careteam', 'loveone', 'members', 'is_admin','to_day'));
    }

    public function createForm($loveone_slug){
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $careteam = careteam::where('loveone_id', $loveone->id)->with(['user'])->get()->keyBy('user_id');
        $date_now = new DateTime();
        $date_now->sub(new DateInterval('P1D'));
        //dd($careteam);
        $readTour = $this->alreadyReadTour('carepoints_create');
        return view('carehub.create_event',compact('loveone','careteam','date_now', 'readTour'));
    }

    public function createUpdate(Request $request)
    {
        $data = $request->all();
        $assigned_ids = $data['assigned'];
        $data['assigned_ids'] = json_encode($data['assigned']);
        $data['creator_id'] = Auth::user()->id;
        //dd($data);
        unset($data['_token']);
        unset($data['assigned']);
        //dd($data);
        // edit
        if($request->id > 0)
            $event = event::where('id',$request->id)->update($data);
        // create
        else{
            $event = event::create($data);

            // Create notification rows
            foreach($assigned_ids as $user_id){
                $notification = [
                    'user_id'    => $user_id,
                    'loveone_id' => $request->loveone_id,
                    'table'      => self::EVENTS_TABLE,
                    'table_id'   => $event->id,
                    'event_date' => $data['date'].' '.$data['time']
                ];
                $this->createNotification($notification);

            }
        }

        $event->assigned = json_decode($event->assigned_ids);
        $users = User::whereIn('id',$event->assigned)->get();
        //dd($users);
            //$user->notification(new EventNotification($event));
            Notification::send($users, new EventNotification ($event));  

       /*     $when = now()->addMinutes(10);
            foreach($users as $user){
                $user->notify((new EventNotification($event))->delay($when));

            }*/
       
        // if ($request->ajax()) 
        return response()->json(['success' => true, 'data' => ['event' => $event]]);
    }

    
    public function getEvents(Request $request)
    {
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        $careteam = careteam::where('loveone_id', $loveone->id)->with(['user'])->get();

        foreach ($careteam as $key => $team){
           // dd($team);
            if(isset($team->user)){
                $team->user->photo = ($team->user->photo != '') ? asset($team->user->photo) :  asset('img/avatar2.png');
                if(Auth::user()->id == $team->user_id && $team->role == 'admin')
                    $is_admin = true;
            }
           
        }
        if($request->type == 1){
            $inidate = $request->date;
            $enddate = $request->date;
        }else if($request->type == 2){
           // dd($request->date);
            $to_date = new DateTime($request->date);
            $calendar = $this->calendar_week_month($to_date->format('Y-m'));
            //$calendar['calendar'][0]['datos'];
            foreach($calendar['calendar'] as $i => $w){
                foreach($w['datos'] as $day){
                    if($day['dia'] == $to_date->format('d')){
                        $week = $calendar['calendar'][$i]['datos'];
                    }
                }
            }
            $inidate = $week[0]['fecha'];
            $enddate = $week[6]['fecha'];
            //dd($inidate,$enddate);
        }else if($request->type == 3){
            $to_date_ini = new Datetime($request->date);
            $to_date_ini->modify('first day of this month');
            $inidate = $to_date_ini->format('Y-m-d');

            $to_date_end = new Datetime($request->date);
            $to_date_end->modify('last day of this month');
            $enddate = $to_date_end->format('Y-m-d');

           
        }
      //  $invitations = Invitation::where('loveone_id', $loveone->id)->get();
        $events_to_day = event::where('loveone_id', $loveone->id)
        ->where('status',1)
        ->whereBetween('date', [$inidate, $enddate])
        ->orderBy("date")->orderBy("time")
        ->with(['messages'])
        ->get()->groupBy("date")->toArray();
   
       
        
        $events = array();
        $time_first_event = '';
        foreach($events_to_day as $cve_event => $event){
            $ftitle_temp = new DateTime($cve_event);

           // $events_to_day[$cve_event]['title'] = $ftitle_temp->format('l, j F Y');
            foreach($event as $cve_day => $day){
                $date_temp = new DateTime($day['date'] . " " . $day['time']);
                $events_to_day[$cve_event][$cve_day]['time_cad_gi'] = $date_temp->format('g:i');
                $events_to_day[$cve_event][$cve_day]['time_cad_a'] = $date_temp->format('a');
                $events_to_day[$cve_event][$cve_day]['members'] = $careteam->whereIn('user_id',json_decode($day['assigned_ids']));
                $events_to_day[$cve_event][$cve_day]['count_messages'] = count($events_to_day[$cve_event][$cve_day]['messages']);
               // dd($careteam->toArray(),json_decode($day['assigned_ids']));
                if($cve_day == 0){
                    $time_first_event = $date_temp->format('g:i a');
                }
            }
            array_push($events,array('title'=> $ftitle_temp->format('l, j F Y'),'data' => $events_to_day[$cve_event], 'date' => $cve_event));
            
        }
       // dd($events);
        $date = new DateTime($request->date);
        return response()->json(['success' => true, 'data' => [
            //    'loveone' => $loveone,
            //    'careteam' => $careteam,
            //    'members' => $members,
            //   'invitations' => $invitations,
            //    'is_admin' => $is_admin,
            'time_first_event' => $time_first_event,
            'events' => $events,
            'date_title' => $date->format('l, j F Y')
        ]]);
    }

    public function getCalendar(Request $request){

        if(isset($request->month)){
            $to_date = new DateTime();
            $calendar = $this->calendar_month($to_date->format('Y-m'));
        }else{
            $to_date = new DateTime($request->month);
            $calendar = $this->calendar_month($to_date->format('Y-m'));
        }
        $calendar['day'] = $this->getDay($to_date->format('Y-m-d'));
        $calendar['week'] = $this->getWeek($this->calendar_week_month($to_date->format('Y-m')),$to_date->format('d'));
        // $calendar['day_medlist'] = $this->getDayMedlist($to_date->format('Y-m-d'));

        //dd($calendar);
        return $calendar;
    }


    public static function calendar_month($month){
        //$mes = date("Y-m");
        $mes = $month;
        //dump($mes);
        //sacar el ultimo de dia del mes
        $daylast =  date("Y-m-d", strtotime("last day of ".$mes));
        //sacar el dia de dia del mes
        $fecha      =  date("Y-m-d", strtotime("first day of ".$mes));
        $daysmonth  =  date("d", strtotime($fecha));
        $montmonth  =  date("m", strtotime($fecha));
        $yearmonth  =  date("Y", strtotime($fecha));
        
        //dump($montmonth);
        // sacar el lunes de la primera semana
        $nuevaFecha = mktime(0,0,0,$montmonth,$daysmonth,$yearmonth);

        //dd($nuevaFecha);
        $diaDeLaSemana = date("w", $nuevaFecha);
        $nuevaFecha = $nuevaFecha - ($diaDeLaSemana*24*3600); //Restar los segundos totales de los dias transcurridos de la semana
        $dateini = date ("Y-m-d",$nuevaFecha);
        //$dateini = date("Y-m-d",strtotime($dateini."+ 1 day"));
        // numero de primer semana del mes
        $semana1 = date("W",strtotime($fecha));
        // numero de ultima semana del mes
        $semana2 = date("W",strtotime($daylast));
        // semana todal del mes
        // en caso si es diciembre
        //dump($semana1, $semana2,date("m", strtotime($mes)),$fecha, $dateini);
        if (date("m", strtotime($mes))==12) {
            $semana = 5;
        }
        else {
          $semana = ($semana2-$semana1)+1;
        }
        // semana todal del mes
        $datafecha = $dateini;
        $calendario = array();
        $iweek = 0;
        while ($iweek < $semana):
            $iweek++;
            //echo "Semana $iweek <br>";
            //
            $weekdata = [];
            for ($iday=0; $iday < 7 ; $iday++){
              // code...
              //dd(date("Y-m-d",strtotime($datafecha."+ 1 day")));
              //$datafecha = date("Y-m-d",strtotime($datafecha."+ 1 day"));
              $datafecha = date("Y-m-d",strtotime($datafecha."+ 1 day"));
              $datanew['mes'] = date("M", strtotime($datafecha));
              $datanew['dia'] = date("d", strtotime($datafecha));
              $datanew['fecha'] = $datafecha;
              $datanew['class'] = '';
              //AGREGAR CONSULTAS EVENTO
              //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
              array_push($weekdata,$datanew);
            }
            $dataweek['semana'] = $iweek;
            $dataweek['datos'] = $weekdata;
            //$datafecha['horario'] = $datahorario;
            array_push($calendario,$dataweek);
        endwhile;
        $nextmonth = date("Y-M",strtotime($mes."+ 1 month"));
        $lastmonth = date("Y-M",strtotime($mes."- 1 month"));
        $month = date("M",strtotime($mes));
        $yearmonth = date("Y",strtotime($mes));
        //$month = date("M",strtotime("2019-03"));
        //dd($calendario);
        $data = array(
          'next' => $nextmonth,
          'month'=> $month,
          'year' => $yearmonth,
          'last' => $lastmonth,
          'calendar' => $calendario,
        );
     //   dd($data);
        return $data;
      }

      public static function calendar_week_month($month){
        //$mes = date("Y-m");
        $mes = $month;
      //  dump($mes);
        //sacar el ultimo de dia del mes
        $daylast =  date("Y-m-d", strtotime("last day of ".$mes));
        //sacar el dia de dia del mes
        $fecha      =  date("Y-m-d", strtotime("first day of ".$mes));
        $daysmonth  =  date("d", strtotime($fecha));
        $montmonth  =  date("m", strtotime($fecha));
        $yearmonth  =  date("Y", strtotime($fecha));
        //$montmonth =  $montmonth -1; 
      //  dump($montmonth);
        // sacar el lunes de la primera semana
        $nuevaFecha = mktime(0,0,0,$montmonth,$daysmonth,$yearmonth);

       // dd($nuevaFecha);
        $diaDeLaSemana = date("w", $nuevaFecha);
        $nuevaFecha = $nuevaFecha - ($diaDeLaSemana*24*3600); //Restar los segundos totales de los dias transcurridos de la semana
        $dateini = date ("Y-m-d",$nuevaFecha);
        //$dateini = date("Y-m-d",strtotime($dateini."+ 1 day"));
        // numero de primer semana del mes
        $semana1 = date("W",strtotime($fecha));
        // numero de ultima semana del mes
        $semana2 = date("W",strtotime($daylast));
        // semana todal del mes
        // en caso si es diciembre
        if (date("m", strtotime($mes))==12) {
            $semana = 5;
        }
        else {
          $semana = ($semana2-$semana1)+1;
        }
        // semana todal del mes
        $datafecha = $dateini;
        $calendario = array();
        $iweek = 0;
        while ($iweek < $semana):
            $iweek++;
            //echo "Semana $iweek <br>";
            //
            $weekdata = [];
            for ($iday=0; $iday < 7 ; $iday++){
              // code...
              $datafecha = date("Y-m-d",strtotime($datafecha."+ 1 day"));
              $datanew['mes'] = date("M", strtotime($datafecha));
              $datanew['dia'] = date("d", strtotime($datafecha));
              $datanew['fecha'] = $datafecha;
              $datanew['class'] = '';
              //AGREGAR CONSULTAS EVENTO
              //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
              array_push($weekdata,$datanew);
            }
            $dataweek['semana'] = $iweek;
            $dataweek['datos'] = $weekdata;
            //$datafecha['horario'] = $datahorario;
            array_push($calendario,$dataweek);
        endwhile;
        $nextmonth = date("Y-M",strtotime($mes."+ 1 month"));
        $lastmonth = date("Y-M",strtotime($mes."- 1 month"));
        $month = date("M",strtotime($mes));
        $yearmonth = date("Y",strtotime($mes));
        //$month = date("M",strtotime("2019-03"));
        $data = array(
          'next' => $nextmonth,
          'month'=> $month,
          'year' => $yearmonth,
          'last' => $lastmonth,
          'calendar' => $calendario,
        );
     //   dd($data);
        return $data;
      }

      public function getWeek($calendar,$date_day){
          $week = '';
          //obtener la semana que abarca el día señalado
          foreach($calendar['calendar'] as $i => $w){
                foreach($w['datos'] as $day){
                    if($day['dia'] == $date_day){
                        $week = $calendar['calendar'][$i]['datos'];
                    }
                }
          }
          //obtener los das que muestra en web
          $dateini = date("Y-m-d",strtotime($week[0]['fecha']));
          $datafecha = $dateini;
          
          $datefin = date ("Y-m-d",strtotime($week[6]['fecha']));
          $datafechaf = $datefin;
          for ($iday=0; $iday < 3 ; $iday++){
            // 3 dias antes de la semana
            $datanewini = array();
            $datafecha = date("Y-m-d",strtotime($datafecha."- 1 day"));
            $datanewini['mes'] = date("M", strtotime($datafecha));
            $datanewini['dia'] = date("d", strtotime($datafecha));
            $datanewini['fecha'] = $datafecha;
            $datanewini['class'] = 'd-none d-sm-none d-lg-block mt-2 ';
            //AGREGAR CONSULTAS EVENTO
            //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            array_unshift($week,$datanewini);
          }
          for ($iday=0; $iday < 2 ; $iday++){
            //3 dias despues 
            $datanewend = array();
            $datafechaf = date("Y-m-d",strtotime($datafechaf."+ 1 day"));
            $datanewend['mes'] = date("M", strtotime($datafechaf));
            $datanewend['dia'] = date("d", strtotime($datafechaf));
            $datanewend['fecha'] = $datafechaf;
            $datanewend['class'] = 'd-none d-sm-none d-lg-block mt-2 ';
            //AGREGAR CONSULTAS EVENTO
            //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            array_push($week,$datanewend);
          }


          return $week;
          
      }


      public function getDay($date){
        $day = array();
        
        $datefin = date ("Y-m-d",strtotime($date));
        $datafechaf = $datefin;
        $datanewend = array();

        $datanewend['mes'] = date("M", strtotime($datafechaf));
        $datanewend['dia'] = date("d", strtotime($datafechaf));
        $datanewend['fecha'] = $datafechaf;
        array_push($day,$datanewend);

        for ($iday=0; $iday < 11 ; $iday++){
          // 3 dias antes de la semana
         
          //3 dias despues 
          $datanewend = array();
          $datafechaf = date("Y-m-d",strtotime($datafechaf."+ 1 day"));
          $datanewend['mes'] = date("M", strtotime($datafechaf));
          $datanewend['dia'] = date("d", strtotime($datafechaf));
          $datanewend['fecha'] = $datafechaf;
          if($iday > 5){
                $datanewend['class'] = 'd-none d-sm-none d-md-block  d-lg-block mt-2';
          }else{
                $datanewend['class'] = '';
          }
          //AGREGAR CONSULTAS EVENTO
          //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
          array_push($day,$datanewend);
        }


        return $day;
    }

   

    public function getEvent(Request $request)
    {

        $event = event::where('id', $request->id)->with('messages','creator')->first();
        $loveone  = loveone::whereSlug($request->slug)->first();
        $careteam = careteam::where('loveone_id', $loveone->id)->with(['user'])->get();
        $is_careteam = false;
        $id_careteam = 0;
        foreach ($careteam as $key => $team){
            if(isset($team->user)){
                $team->user->photo = ($team->user->photo != '') ? $team->user->photo :  asset('img/avatar2.png');
            }
        }

        $event->members = $careteam->whereIn('user_id',json_decode($event->assigned_ids));
        foreach($event->members as $member){
            if(Auth::user()->id == $member->user_id ){
                $is_careteam = true;
                $id_careteam = $member->id;
            }
        }

        $date_temp = new DateTime($event->date . " " . $event->time);
        
        $event->time_cad_gi = $date_temp->format('g:i');
        $event->time_cad_a = $date_temp->format('a');
        $event->date_title = $date_temp->format('l, m.j.Y');
        
        //dd($event->messages);
        foreach ($event->messages as $key => $message){
            $date_temp_m = new DateTime($message->date . " " . $message->time);

            $date_now = new DateTime();
            $interval = $date_temp_m->diff($date_now);
           // dump($date_temp, $date_now, $interval,);

            if($interval->format('%H') == 0){
                $event->messages[$key]->date_title_msj = $interval->i .'m ago';
            }else if($interval->format('%H') > 0 && $interval->format('%H') < 24){
                $event->messages[$key]->date_title_msj = $interval->format('%H h %i m') .' ago';
            }else{
                $event->messages[$key]->date_title_msj = $date_temp_m->format('j M Y');
            }

            $event->messages[$key]->time_cad_gi = $date_temp_m->format('g:i');
            $event->messages[$key]->time_cad_a = $date_temp_m->format('a');
            $event->messages[$key]->date_title = $date_temp_m->format('j M Y');
            $event->messages[$key]->creator_img = ($message->creator->user->photo != '') ? $message->creator->user->photo :  asset('img/avatar2.png');
            
         }
         $event->creator->photo = ($event->creator->photo != '') ? $event->creator->photo :  asset('img/avatar2.png');
         //dd();
        // dd($event);
        return view('carehub.event_detail',compact('event','is_careteam','id_careteam'));

    }

    public function deleteEvent(Request $request){
        //dd($request->id);
        $event = event::find($request->id)->update(['status' => 0]);
        return response()->json(['success' => true, 'data' => [
            'success' => 1
        ]]);
    }


}
