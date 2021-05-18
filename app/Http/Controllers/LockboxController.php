<?php

namespace App\Http\Controllers;

use App\Models\lockbox;
use App\Models\lockbox_types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** 
         * $member->photo = ($member->photo != '') ? env('APP_URL').'/public'.$member->photo :  asset('public/img/avatar2.png');
        */
        if ($request->ajax()) {
            $types     = lockbox_types::all();
            $documents = lockbox::where('user_id',Auth::Id())->get();
            foreach($types as &$t){
                foreach($documents as $i => $d){
                    if($t->id == $d->lockbox_types_id && $t->required == 1){
                        $t->file = $d;
                        unset($documents[$i]);
                        break;
                    }
                }
            }
            return array('types' => $types, 'documents' => $documents);
        }
        return view('lockbox.index');
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
            'name'             => 'required|max:150',
            'description'      => 'nullable|max:400',
            'file'             => 'required|file',
            'status'           => 'required|boolean',

        ]);
            
        $repo = 'uploads/' . $request->id_user;

        if ($request->hasFile('file')){
            $fileName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs($repo, $fileName, 'public');
            
            $doct = new Lockbox;
            $doct->user_id          = $request->user_id; //change to user_id
            $doct->lockbox_types_id = $request->lockbox_types_id; //change to types
            $doct->name             = $request->name;
            $doct->description      = $request->description;      
            $doct->status           = $request->status;
            $doct->file             = '/storage/' . $filePath;
            $doct->save();
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
            
        $repo = 'uploads/' . $request->id_user;

        $doct              = Lockbox::find($request->id);
        $doct->name        = $request->name;
        $doct->description = $request->description;      
        $doct->status      = $request->status;

        if ($request->hasFile('file')){
            $fileName   = time().'_'.$request->file->getClientOriginalName();
            $filePath   = $request->file('file')->storeAs($repo, $fileName, 'public');
            $doct->file = '/storage/' . $filePath;
        
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
    public function destroy(lockbox $lockbox)
    {
        //
    }
}
