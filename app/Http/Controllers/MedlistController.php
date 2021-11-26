<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use function GuzzleHttp\json_decode;
use App\Http\Traits\NotificationTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\medication;
use App\Models\medlist;
use App\Models\route;
use App\Models\dosage;
use GuzzleHttp\Client;
use App\Models\loveone;
use App\Models\event;
use App\Models\Invitation;
use App\Models\careteam;
use Illuminate\Support\Collection;

use App\User;

class MedlistController extends Controller
{
    use NotificationTrait;
    protected $apikey = "b2fd462e298e18c8345d3ec9490589cf";
    const MEDICATIONS_TABLE = 'medications';

    public function index(Request $request)
    {   
        $is_admin = false;
        $to_day = new DateTime();
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        if(!$loveone){
            return view('errors.not-found');
        }
        /* Seguridad */
        if(!Auth::user()->permission('medlist',$loveone->id))
        {
            return redirect('/home')->with('err_permisison', 'You don\'t have permission to access MedList');  
        }
            
        $careteam = careteam::where('loveone_id', $loveone->id)->get()->keyBy('user_id');
        $membersIds = $careteam->pluck('user_id')->toArray();
        $members = User::whereIn('id', $membersIds)->get();
        $events = event::where('loveone_id', $loveone->id)->get();
        foreach ($members as $key => $member){
            $members[$key]['careteam'] = $careteam[$member->id];
            if(Auth::user()->id == $member->id && $careteam[$member->id]->role == 'admin')
                $is_admin = true;
        }

        $this->areNewNotifications($request->loveone_slug, Auth::user()->id);



        return view('medlist.index',compact('events','careteam', 'loveone', 'members', 'is_admin','to_day'));
    }

    public function createForm($loveone_slug)
    {
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $routes  = route::orderBy('route')->get();
       // $dosages  = dosage::orderBy('dosage')->get();
        $careteam = careteam::where('loveone_id', $loveone->id)->with(['user'])->get()->keyBy('user_id');
        $date_now = new DateTime();
        $date_now->sub(new DateInterval('P1D'));

        $readTour = $this->alreadyReadTour('medlist_create');
        return view('medlist.create_medication',compact('loveone','careteam','date_now','routes', 'readTour'));
    }

    public function createUpdate(Request $request)
    {

        $data = $request->all();
        if($data['frequency'] == 'as needed'){
            $data['time'] = '00:00';
        }
        $remind = $data['remind'];
        unset($data['_token']);
        unset($data['assigned']);
        unset($data['remind']);
        $date_i = $date = new DateTime(date('Y-m-d').' '.$data['time']);
        $data['ini_date'] = $date->format('Y-m-d');
        $di = $date_i->format('Y-m-d H:i:s');
        $end_date = $date->add(new DateInterval('P'.$data['days'].'D'));
        $data['end_date'] = $end_date->format('Y-m-d');


        // edit
        if($request->id > 0)
            $medication = medication::where('id',$request->id)->update($data);
        // create
        else{
            $medication = medication::create($data);
            $medlist = $this->medicationTime($di,$end_date->format('Y-m-d H:i:s'),$data['frequency'],$medication->id);
            medlist::insert($medlist);

            // Create notification rows

            $notified_members = $this->getLovedoneMembersToBeNotified($request->loveone_id, 'medlist');
            foreach($notified_members as $member_id){

                if($remind){
                    foreach ($medlist as $med) {
                        
                        $notification = [
                            'user_id'    => $member_id,
                            'loveone_id' => $request->loveone_id,
                            'table'      => self::MEDICATIONS_TABLE,
                            'table_id'   => $medication->id,
                            'event_date' => $med['date'].' '.$med['time']
                        ];
                        $this->createNotification($notification);
                    }
                }


                $notification = [
                    'user_id'    => $member_id,
                    'loveone_id' => $request->loveone_id,
                    'table'      => self::MEDICATIONS_TABLE,
                    'table_id'   => $medication->id,
                    'event_date' => $medication->refill_date.' 00:00:00'
                ];
                $this->createNotification($notification);
            }
        }

        return response()->json(['success' => true, 'data' => ['medication' => $medication]]);
    }


    public function medicationTime($fechaInicio,$fechaFin,$hour,$medication_id){
        
        $medications = array();
        # Fecha como segundos
        $tiempoInicio = strtotime($fechaInicio);
        $tiempoFin = strtotime($fechaFin);

        #60 minutos por hora * 60 segundos por minuto
        if($hour == 'as needed'){
            $hour = 24;
        }
        $hour = 3600 * $hour;
        while($tiempoInicio < $tiempoFin){
            $f = array();
            
            $f['date'] = date("Y-m-d", $tiempoInicio);
            $f['time'] = date("H:i:s", $tiempoInicio);
            $f['medication_id'] = $medication_id;
            $f['created_at'] = date("Y-m-d H:i:s");
            $f['updated_at'] = date("Y-m-d H:i:s");


            # Sumar el incremento de horas
            array_push($medications, $f);
            $tiempoInicio += $hour;
        }
        return $medications;

    }


    public function getMedications(Request $request)
    {
        //dd($_POST);
        $loveone  = loveone::whereSlug($request->loveone_slug)->first();
        $next_dosage = '';
        $time_first_event = '';
        $inidate = $request->date;
        $modal = $medlist = array();
        $medications = medication::where('loveone_id', $loveone->id)
        ->where(function ($query) use($inidate){
            $query->where('ini_date', '<=', $inidate)
                ->where('end_date', '>=', $inidate);
        })
        ->orderBy("ini_date")
        ->with(['medlist'])
        ->orderBy('ini_date')
        ->get();
        //dd($medications);
        $count_medications = $medications->count();
        foreach($medications as $medication){

            foreach($medication->medlist as $medicine){
                $med = array();
                $med['id'] = $medicine->id;
                $med['medication_id'] = $medicine->medication_id;
                $med['date'] = $medicine->date;
                $med['time'] = $medicine->time;
                $med['dosage'] = $medication->dosage;
                $med['prescribing'] = $medication->prescribing;
                $med['drugbank_pcid'] = $medication->drugbank_pcid;
                $med['route'] = $medication->route;
                $med['frequency'] = $medication->frequency;
                $med['medicine'] = $medication->medicine;
                $date_temp = new DateTime($medicine->date . " " . $medicine->time);
                $med['date_usa'] = $date_temp->format('Y-d-m');

                $date_now = new DateTime();
                $med['time_cad_gi'] = $date_temp->format('g:i');
                $med['time_cad_a'] = $date_temp->format('a');
                if($date_now->format('H:i:s') >= $date_temp->format('H:i:s')){
                    $med['status'] = 1;
                }
                if($medicine->date == $inidate){
                    array_push($medlist,$med);
                }
                $modal[$medication->id][] = $med;
            }
        }
        usort($medlist, function ($a, $b) {
            return strcmp($a["time"], $b["time"]);
        });

        if(count($medlist) >0){
            $date_now = new DateTime();
            foreach($medlist as $med){
                $date_temp = new DateTime($med['date'] . " " . $med['time']);
                if($date_now->format('H:i:s') <= $date_temp->format('H:i:s') && $next_dosage == '' ){
                    $next_dosage = $med['medicine']. ' at ' . $med['time_cad_gi']. ' '. $med['time_cad_a'];
                }
            }
        }
        
        
        $date = new DateTime($request->date);
        return response()->json(['success' => true, 'data' => [
            'time_first_event' => $time_first_event,
            'medlist' => $medlist,
            'date_title' => $date->format('l, j F Y'),
            'medlist_modal' => $modal,
            'count_medications' => count($medlist),
            'next_dosage' => $next_dosage
        ]]);
    }

    


    public function getCalendar(Request $request){

            $to_date = new DateTime();
           
        $calendar['day_medlist'] = $this->getDayMedlist($to_date->format('Y-m-d'));

        //dd($calendar);
        return $calendar;
    }


    public function getDayMedlist($date){
        $day = array();
        
        $datefin = date ("Y-m-d",strtotime($date));
        $datafecha = $datafechaf = $datefin;
        
        $datanewend = array();

        $datanewend['mes'] = date("M", strtotime($datafechaf));
        $datanewend['dia'] = date("d", strtotime($datafechaf));
        $datanewend['dia_nom'] = date("D", strtotime($datafechaf));

        $datanewend['fecha'] = $datafechaf;
        array_push($day,$datanewend);

        for ($iday=0; $iday < 4 ; $iday++){
          // 3 dias antes de la semana
         $datanewini = array();
         $datafecha = date("Y-m-d",strtotime($datafecha."- 1 day"));
         $datanewini['mes'] = date("M", strtotime($datafecha));
         $datanewini['dia'] = date("d", strtotime($datafecha));
         $datanewini['dia_nom'] = date("D", strtotime($datafecha));
         $datanewini['fecha'] = $datafecha;

         //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
         array_unshift($day,$datanewini);
          //3 dias despues 
          $datanewend = array();
          $datafechaf = date("Y-m-d",strtotime($datafechaf."+ 1 day"));
          $datanewend['mes'] = date("M", strtotime($datafechaf));
          $datanewend['dia'] = date("d", strtotime($datafechaf));
          $datanewend['dia_nom'] = date("D", strtotime($datafechaf));

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

    public function getMedicineSearch(Client $client, Request $request){
        //dd($request->keyword);// keyword
        $medicines = array();
        $response = $client->request('GET', 'https://api.drugbank.com/v1/us/product_concepts?q='.$request->keyword,[
        'headers' => ['Authorization' => $this->apikey]
        ]);
        $resp = json_decode($response->getBody());
        foreach($resp as $m){
            $tmp = array();
            
            foreach($m->hits as $hit){
                $tmp['hit'] = $hit->value;
                $tmp['name'] = $m->name;
                $tmp['drugbank_pcid'] = $m->drugbank_pcid;
                array_push($medicines,$tmp);
            }
            

        }
        return response()->json(['success' => true, 'medicines' => $medicines]);
    }

    public function getRouteSearch(Client $client, Request $request){
       // dd($request->keyword);// keyword
        $medicines = array();
        $response = $client->request('GET', 'https://api.drugbank.com/v1/us/product_concepts/'.$request->keyword.'/routes',[
        'headers' => ['Authorization' => $this->apikey]
        ]);
        $routes = json_decode($response->getBody());
        return response()->json(['success' => true, 'routes' => $routes]);
    }

    function getStrengthSearch(Client $client, Request $request){
        // dd($request->keyword);// keyword
        $medicines = array();
        $response = $client->request('GET', 'https://api.drugbank.com/v1/us/product_concepts/'.$request->keyword.'/strengths',[
        'headers' => ['Authorization' => $this->apikey]
        ]);
        $strengths = json_decode($response->getBody());
        return response()->json(['success' => true, 'strengths' => $strengths]);
    }


    function getWikiSearch(Client $client, Request $request){
        // dd($request->keyword);// keyword
        $medicines = array();
        $response = $client->request('GET', 'https://api.drugbank.com/v1/us/product_concepts/'.$request->keyword,[
        'headers' => ['Authorization' => $this->apikey]
        ]);
        $products = json_decode($response->getBody());
        //dd($products);
        return response()->json(['success' => true, 'products' => $products]);
    }

    function deleteMedlist(Request $request){
       // dd($request->medlist, $request->slug);
        medlist::where('medication_id',$request->medlist['medication_id'])->delete();
        medication::where('id',$request->medlist['medication_id'])->delete();
        //dd($request->medlist['medication_id'],$med->get());
        return response()->json(['success' => true, 'data' => [
            'success' => 1,
            'slug' => $request->slug
        ]]);

    }
}
