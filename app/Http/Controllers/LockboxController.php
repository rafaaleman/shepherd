<?php

namespace App\Http\Controllers;
use App\Models\lockbox;
use App\Models\loveone;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendMissingMail;
use App\Models\careteam;
use App\User;
use Illuminate\Http\Request;
use App\Models\lockbox_types;
use App\Models\lockbox_permissions;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\NotificationTrait;
use Session;

/**
 * 
 * id
 * id_user
 * lockbox_types_id
 * name
 * description
 * file
 * status               
 */
class LockboxController extends Controller
{

    use NotificationTrait;
    const EVENTS_TABLE = 'lockbox';
    const EVENTS_TABLE_P = 'lockbox_permission';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $careteam = array();
        $isAdmin = 0 ;
        $tmpUser = null;

        $this->areNewNotifications($request->loveone_slug, Auth::user()->id);
        $readTour = $this->alreadyReadTour('lockbox_index');
        /** 
         * $member->photo = ($member->photo != '') ? env('APP_URL').'/public'.$member->photo :  asset('public/img/avatar2.png');
        */

        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();

        if(!$loveone){
           // dd("no existe");
        }

        /* Seguridad */
        if(!Auth::user()->permission('lockbox',$loveone->id))
        {
            return redirect('/home')->with('err_permisison','You don\'t have permission to access LockBox');  
        }

        $cm = careteam::where('loveone_id',$loveone->id)->get();

        $p = json_encode("{'user':0, 'r' : 0 }");
        
        foreach($cm as $c)
        {
            if($c->user_id == Auth::user()->id){
                $tmpUser = $c;
                break;
            }
        }

        
        if($tmpUser->role === "admin")
        {
            foreach($cm as $u)
            {
                $role = "";
                $user = $u->user;
                if( $u->role  == "admin" ){ $role ='admin'; }
                else{ $role ="user"; }

                if(Auth::user()->id == $user->id && $u->role  == "admin" ){ $isAdmin = 1; }

                $careteam[] =array('id'=>$user->id,'name'=> $user->name . ' ' . $user->lastname,'photo' => $user->photo, 'status' => $user->status,'role' => $role, 'permissions' => $p);            
            }

            if ($request->ajax()) 
            {           
                $types     = lockbox_types::where('status',1)->get();            
                $documents = lockbox::where('loveones_id',$loveone->id)
                                ->get();
            
                //Obligatorios
                foreach($types as &$t)
                {
                    $t->asFile = false;
                    foreach($documents as $i => $d)
                    {
                        if($t->id == $d->lockbox_types_id && $t->required == 1)
                        {
                            $t->file = $d;
                            $t->asFile = true;
                            unset($documents[$i]);
                            break;
                        }
                    }
                }
            
                /*last 5*/
                $last = lockbox::where('loveones_id',$loveone->id)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
            
                foreach($last as &$d)
                {
                    $d->permissions  = \json_decode($d->permissions);
                }

                return array('types' => $types,'careteam' => $careteam, 'documents' => $documents,'lastDocuments' => $last ,'slug' => $loveone_slug );
            }

            return view('lockbox.index',compact('loveone','loveone_slug','careteam', 'readTour'));
        }else{
            
            if ($request->ajax()) 
            {           
                $types     = lockbox_types::where('status',1)->get();                 
                $tmp_documents = lockbox::where('loveones_id',$loveone->id)
                                        ->get();
                $documents     = array();
                $last          = array();

                foreach($tmp_documents as $i => $d)
                {
                    foreach($d->permissions as $r)
                    {
                        if($r->user_id == Auth::user()->id && $r->r == 1)
                        {
                            $documents[] = $d;
                        }
                    }
                }
            
                //Obligatorios
                foreach($types as &$t)
                {
                    $t->asFile = false;
                    foreach($documents as $i => $d)
                    {
                        if($t->id == $d->lockbox_types_id && $t->required == 1)
                        {
                            $t->file = $d;
                            $t->asFile = true;
                            unset($documents[$i]);
                            break;
                        }
                    }
                }
                /*last 5*/
                $tmp_last = lockbox::where('loveones_id',$loveone->id)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        
                foreach($tmp_last as $i => $d)
                {
                    foreach($d->permissions as $r)
                    {
                        if($r->user_id == Auth::user()->id && $r->r == 1)
                        {
                            $last[] = $d;
                        }
                    }
                }
                
                foreach($last as &$d)
                {
                    $d->permissions  = \json_decode($d->permissions);
                }


                return array('types' => $types,'documents' => $documents,'lastDocuments' => $last ,'slug' => $loveone_slug );
            }
            
            return view('lockbox.index_user',compact('loveone','loveone_slug', 'readTour'));
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'user_id'          => 'required|numeric',
            'lockbox_types_id' => 'required|numeric',
            'loveones_id'      => 'required|numeric',
            'name'             => 'required|max:150',
            'description'      => 'nullable|max:400',
            'file'             => 'required|file',
            'status'           => 'required|boolean',

        ]);

        $permisisons = \json_decode($request->permissions);

        $repo = 'loveones/lockbox/' . $request->loveones_id;
        
        if ($request->hasFile('file')){
            $fileName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $repo .'/'. $fileName;
            //$filePath = $request->file('file')->storeAs($repo, $fileName, 'public');
            
            $request->file('file')->move(public_path($repo), $fileName);

            $doct = new Lockbox;
            $doct->user_id          = $request->user_id; 
            $doct->lockbox_types_id = $request->lockbox_types_id; 
            $doct->loveones_id      = $request->loveones_id;
            $doct->name             = $request->name;
            $doct->description      = $request->description;      
            $doct->status           = $request->status;
            $doct->file             =  '/'.$filePath;
            
            
            if($doct->save()){
                foreach($permisisons as $p){
                    $perm = new lockbox_permissions;
                    $perm->user_id    = $p->user;
                    $perm->lockbox_id = $doct->id; 
                    $perm->r          = $p->r;
                    $perm->u          = 0;
                    $perm->d          = 0;            
                    $perm->save();
                }

                $this->createNotifications($request->loveones_id, $doct->id);
    
                return response()->json(['success' => true, 'data' => ['msg' => 'Document created!']], 200);
            }else{
                return response()->json(['success' => false, 'error' => '...']);        
            }            
        }
        return response()->json(['success' => false, 'error' => '...']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\lockbox  $lockbox
     * @return \Illuminate\Http\Response
     */
    public function show(lockbox $lockbox)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\lockbox  $lockbox
     * @return \Illuminate\Http\Response
     */
    public function edit(lockbox $lockbox)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\lockbox  $lockbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, lockbox $lockbox)
    {
        $validatedData = $request->validate([
            'id'          => 'required|numeric',
            'user_id'          => 'required|numeric',
            'lockbox_types_id' => 'required|numeric',
            'name'             => 'required|max:150',
            'description'      => 'nullable|max:400',
            'status'           => 'required|boolean',

        ]);
      

        $permisisons = \json_decode($request->permissions);
        
        //$repo = 'uploads/' . $request->user_id;
        $repo = 'loveones/' . $request->loveones_id;

        $doct              = Lockbox::find($request->id);
        $doct->name        = $request->name;
        $doct->description = $request->description;      
        $doct->status      = $request->status;

        
        if ($request->hasFile('file')){
            if(\File::exists(public_path($doct->file))){
                \File::delete(public_path($doct->file));
                $doct->delete();
            }
            $fileName   = time().'_'.$request->file->getClientOriginalName();
            //$filePath   = $request->file('file')->storeAs($repo, $fileName, 'public');
            //$doct->file = '/storage/' . $filePath;
            $filePath = $repo .'/'. $fileName;
            //$filePath = $request->file('file')->storeAs($repo, $fileName, 'public');
            
            $request->file('file')->move(public_path($repo), $fileName);
            $doct->file = "/" . $filePath;
        }

        
        if($doct->save()){
            foreach($permisisons as $p){
                $tmp = lockbox_permissions::where(['user_id' => $p->user, 'lockbox_id' => $request->id])->first();
                if($tmp->r == 0 && $p->r == 1){
                    $notification = [
                        'user_id'    => $p->user,
                        'loveone_id' => $request->loveones_id,
                        'table'      => self::EVENTS_TABLE,
                        'table_id'   => $request->id,
                        'event_date' => date('Y-m-d H:i:s')
                    ];
                    $this->createNotification($notification);
                }
                $perm = lockbox_permissions::updateOrCreate(
                    ['user_id' => $p->user, 'lockbox_id' => $request->id],
                    ['r' => $p->r, 'u' => 0, 'd'=> 0]
                );

            }
            

            return response()->json(['success' => true, 'data' => ['msg' => 'Document created!']], 200);
        }else{
            return response()->json(['success' => false, 'error' => '...']);        
        }   

        return response()->json(['success' => false, 'error' => '...']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\lockbox  $lockbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $doct = Lockbox::find($request->id_doc);
        if(\File::exists(public_path($doct->file))){
            \File::delete(public_path($doct->file));
            
            $doct->delete();
            }else{
                dd(public_path($doct->file));
            }
        return response()->json(['success' => true, 'data' => ['msg' => 'Document deleted!'],'docto' => $doct ], 200);
    }

    public function lastDocuments(Request $request)
    {
        $num = 5;
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $documents = lockbox::where('loveones_id',$loveone->id)
                            ->orderBy('updated_at', 'desc')
                            ->take($num)
                            ->get();

        return array('documents' => $documents,'slug' => $loveone_slug );
    }

    public function countDocuments(Request $request)
    {
        $loveone_slug = $request->loveone_slug;
        $loveone  = loveone::whereSlug($loveone_slug)->first();
        $documents = lockbox::where('loveones_id',$loveone->id)->count();
        $num_documents = lockbox::where('loveones_id',$loveone->id)
                            ->orderBy('updated_at', 'desc')
                            ->take(1)
                            ->get();
    $l ="No documents yet";
    if(count($num_documents) > 0){
        $l = $num_documents[0]->updated_at->diffForHumans();
    }
        return response()->json(['success' => true, 'data' => ['l_document'=>$l,'num_documents' => $num_documents,'documents' => $documents]], 200);
    }

    /** */
    public function checkEssentialDocuments(){
        $loveones  = loveone::all();
        $types     = lockbox_types::where('status',1)->get();
        foreach($loveones as $loveone){
            $mail = array();
            $d = array();

            $cm = careteam::where('loveone_id',$loveone->id)->where('role','admin')->first();
            $user = User::find($cm->user_id);
            $documents = lockbox::where('loveones_id',$loveone->id)->get();
            
            $email['loveone_name'] = $loveone->firstname;
            $email['loveone_photo'] = $loveone->photo;
            $email['name'] = $user->name . ' ' . $user->lastname;
            $email['email'] = $user->email;
            $email['documents'] = array();

            foreach($types as $t){
                if($t->required == 1){
                    $d["name"] = $t->name;
                    $d["exist"] = false;
                    foreach($documents as $doc){
                        if($t->id == $doc->lockbox_types_id){
                            $d["exist"] = true;
                        }
                    }
                    $email['documents'][] = $d;
                }
            }

           Mail::to($user->email)->send(new sendMissingMail($email));
        }
            return true;
    }

    /**
     * 
     */
    protected function createNotifications($loveone_id, $lockbox_table_id)
    {
        $team_members = careteam::where('loveone_id', $loveone_id)->get();

        // Create notification rows
        foreach($team_members as $user){
            $notification = [
                'user_id'    => $user->user_id,
                'loveone_id' => $loveone_id,
                'table'      => self::EVENTS_TABLE,
                'table_id'   => $lockbox_table_id,
                'event_date' => date('Y-m-d H:i:s')
            ];
            $this->createNotification($notification);
        }
    }
}
