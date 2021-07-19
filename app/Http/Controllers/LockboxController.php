<?php

namespace App\Http\Controllers;
use App\Models\lockbox;
use App\Models\loveone;

use App\Models\careteam;
use App\User;
use Illuminate\Http\Request;
use App\Models\lockbox_types;

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $careteam = array();
       $isAdmin= 0 ;
        $this->areNewNotifications($request->loveone_slug, Auth::user()->id);
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
            return redirect('/home')->with('err_permisison', "You don't have permission to Lockbox!");  
        }

        

        $cm = careteam::where('loveone_id',$loveone->id)->get();
        $p = json_encode("{ 'user' : 0, 'r' : 0 , 'u': 0, 'd': 0}");
        foreach($cm as $u){
            $role = "";
            $user = $u->user;
            if(Auth::user()->id == $user->id || $u->role  == "admin" ){ $role ='admin'; }
            else{ $role ="user"; }

            if(Auth::user()->id == $user->id && $u->role  == "admin" ){ $isAdmin = 1; }

            $careteam[] =array('id'=>$user->id,'name'=> $user->name . ' ' . $user->lastname,'photo' => $user->photo, 'status' => $user->status,'role' => $role, 'permissions' => $p);            
        }

        if ($request->ajax()) 
        {
            $types     = lockbox_types::all();
            $documents = lockbox::where('loveones_id',$loveone->id)
                                ->get();
            foreach($documents as $i => &$d){
                $d->permissions  = \json_decode($d->permissions);
            }

            foreach($types as &$t){
                $t->asFile = false;
                foreach($documents as $i => $d){
                    if($t->id == $d->lockbox_types_id && $t->required == 1){
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
            foreach($last as &$d){
                $d->permissions  = \json_decode($d->permissions);
            }
            return array('types' => $types,'careteam' => $careteam, 'documents' => $documents,'lastDocuments' => $last ,'slug' => $loveone_slug,'isAdmin' =>$isAdmin );
        }

        

        return view('lockbox.index',compact('loveone','loveone_slug','careteam'));
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
            
        $repo = 'loveones/lockbox/' . $request->loveones_id;
        
        if ($request->hasFile('file')){
            $fileName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $repo .'/'. $fileName;
            //$filePath = $request->file('file')->storeAs($repo, $fileName, 'public');
            
            $request->file('file')->move(public_path($repo), $fileName);

            $doct = new Lockbox;
            $doct->user_id          = $request->user_id; //change to user_id
            $doct->lockbox_types_id = $request->lockbox_types_id; //change to types
            $doct->loveones_id      = $request->loveones_id; //change to types
            $doct->name             = $request->name;
            $doct->description      = $request->description;      
            $doct->status           = $request->status;
            $doct->permissions      = $request->permissions;

            $doct->file             =  '/'.$filePath;
            $doct->save();
            
            $this->createNotifications($request->loveones_id, $doct->id);

            return response()->json(['success' => true, 'data' => ['msg' => 'Document created!']], 200);
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
        $doct->save();
        return response()->json(['success' => true, 'data' => ['msg' => 'Document created!']], 200);

        //return response()->json(['success' => false, 'error' => '...']);
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
        

        return response()->json(['success' => true, 'data' => ['documents' => $documents]], 200);
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
