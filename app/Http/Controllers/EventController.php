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



class EventController extends Controller
{
    public function index(Request $request)
    {   
        
        $to_day = new DateTime();
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        if($loveone){
            
            $careteam = careteam::where('loveone_id', $loveone->id)->get()->keyBy('user_id');
            $membersIds = $careteam->pluck('user_id')->toArray();
            $members = User::whereIn('id', $membersIds)->get();
            $events = event::where('loveone_id', $loveone->id)->get();
            foreach ($members as $key => $member){
                $members[$key]['careteam'] = $careteam[$member->id];
                if(Auth::user()->id == $member->id && $careteam[$member->id]->role == 'admin')
                    $is_admin = true;
            }

        } else {

            $loveone = null;
            $membersIds = null;
            $members = null;
            $events = null;
            $careteam = null;
            $is_admin = false;
        }
        //dd($loveone);

        return view('carehub.index',compact('events','careteam', 'loveone', 'members', 'is_admin','to_day'));
    }

    public function createForm($loveone_slug){
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $careteam = careteam::where('loveone_id', $loveone->id)->with(['user'])->get()->keyBy('user_id');
        //dd($careteam);
        return view('carehub.create_event',compact('loveone','careteam'));
    }

    public function createUpdate(Request $request)
    {
        $data = $request->all();
        //dd($data);
        //$data['assigned_ids'] = implode(',', $request->assigned);
        //$relationship_id = $data['relationship_id'];
        //$data['phone']   = intval($data['phone']);
       // $data['slug']    = Str::slug($data['firstname'].' '.$data['lastname'].' '.time());
        $data['assigned_ids'] = implode(',',$request->assigned);
        unset($data['_token']);
        unset($data['assigned']);
        //unset($data['relationship_id']);

        // edit
        if($request->id > 0)
            $event = event::update($data);
        // create
        else{
            $event = event::create($data);
            //$this->createCareteam(Auth::user()->id, $loveone->id, $relationship_id);
        }

        // if ($request->ajax()) 
        return response()->json(['success' => true, 'data' => ['event' => $event]]);
    }

    
    public function getEvents(Request $request)
    {
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        //dd($request->date);
        //$careteam = careteam::where('loveone_id', $loveone->id)->get()->keyBy('user_id');
        //$membersIds = $careteam->pluck('user_id')->toArray();
        //$members = User::whereIn('id', $membersIds)->get();
      /*  $is_admin = false;

        foreach ($members as $key => $member){
            $careteam[$member->id]->permissions = unserialize($careteam[$member->id]->permissions);
            $members[$key]['careteam'] = $careteam[$member->id];
            $member->photo = ($member->photo != '') ? env('APP_URL').'/public'.$member->photo :  asset('public/img/avatar2.png');
            if(Auth::user()->id == $member->id && $careteam[$member->id]->role == 'admin')
                $is_admin = true;
        }*/
        if($request->type == 1){
            $inidate = $request->date;
            $enddate = $request->date;
        }else if($request->type == 2){
            $to_date = new DateTime($request->date);
            $calendar = $this->calendar_month($to_date->format('Y-m'));
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

           
            //dd($inidate,$enddate);
        }
      //  $invitations = Invitation::where('loveone_id', $loveone->id)->get();
        $events_to_day = event::where('loveone_id', $loveone->id)
        ->whereBetween('date', [$inidate, $enddate])
        ->orderBy("date")->orderBy("time")
        //->with(['comments'])
        ->get()->groupBy("date")->toArray();
   
       
        //dd($events);
        //$events_to_day = $events->whereBetween('date', [$inidate, $enddate]);
            //->orderBy('date');
            //->where('date',$request->date);
        $events = array();
        foreach($events_to_day as $cve_event => $event){
            $ftitle_temp = new DateTime($cve_event);
           // $events_to_day[$cve_event]['title'] = $ftitle_temp->format('l, j F Y');
            foreach($event as $cve_day => $day){
                //dd($day);
                $date_temp = new DateTime($day['date'] . " " . $day['time']);
                $events_to_day[$cve_event][$cve_day]['time_cad_gi'] = $date_temp->format('g:i');
                $events_to_day[$cve_event][$cve_day]['time_cad_a'] = $date_temp->format('a');
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
            'events' => $events,
            'events_to_day' => $events,
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
        $calendar['week'] = $this->getWeek($calendar,$to_date->format('d'));
        $calendar['day'] = $this->getDay($to_date->format('Y-m-d'));
        //dd($calendar);
        return $calendar;
    }


    public static function calendar_month($month){
        //$mes = date("Y-m");
        $mes = $month;
        //sacar el ultimo de dia del mes
        $daylast =  date("Y-m-d", strtotime("last day of ".$mes));
        //sacar el dia de dia del mes
        $fecha      =  date("Y-m-d", strtotime("first day of ".$mes));
        $daysmonth  =  date("d", strtotime($fecha));
        $montmonth  =  date("m", strtotime($fecha));
        $yearmonth  =  date("Y", strtotime($fecha));
        // sacar el lunes de la primera semana
        $nuevaFecha = mktime(0,0,0,$montmonth,$daysmonth,$yearmonth);
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
            $datanewini['class'] = 'd-none d-sm-block';
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
            $datanewend['class'] = 'd-none d-sm-block';
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
                $datanewend['class'] = 'd-none d-sm-block';
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

        $event = event::where('id', $request->event)->first();
        $date_temp = new DateTime($event->date . " " . $event->time);
        $event->time_cad_gi = $date_temp->format('g:i');
        $event->time_cad_a = $date_temp->format('a');
        $event->date_title = $date_temp->format('l, j F Y');
        return view('carehub.comments_event',compact('event'));

    }



}
