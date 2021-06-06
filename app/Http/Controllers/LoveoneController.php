<?php

namespace App\Http\Controllers;

use App\Models\loveone;
use App\Models\careteam;
use App\Models\condition;
use Illuminate\Support\Str;
use App\Models\relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CreateLoveoneRequest;

class LoveoneController extends Controller
{
   

    /**
     * Shows the create loveone form
     */
    public function index()
    {
        $relationships = relationship::where('status', 1)->get();
        $conditions    = condition::where('status', 1)->get();
        return view('loveone.create', compact('relationships', 'conditions'));
    }

    /**
     * 
     */
    public function setLoveone(Request $request)
    {
        $loveone  = loveone::find($request->id);

        if($loveone){
            return response()->json(['loveone' => $loveone]);
        } else {
            return response()->json(['loveone' => null]);
        }
    }

    /**
     * Creates a loveone register
     */
    public function createUpdate(Request $request)
    {
        $data = $request->all();
        $data = json_decode($data['loveone']);
        $data = collect($data)->toArray();
        // dd($data);

        $data['conditions'] = implode(',', $data['conditions']);
        $relationship_id = $data['relationship_id'];
        $data['phone']   = intval($data['phone']);
        $data['slug']    = Str::slug($data['firstname'].' '.$data['lastname'].' '.time());
        
        unset($data['_token']);
        unset($data['relationship_id']);
        unset($data['photo']);

        // Verify if exists
        $loveone = loveone::where('email', $data['email'])->orWhere('phone', $data['phone'])->first();
        // dd($loveone);
        if($loveone){
            return response()->json(['success' => false, 'error' => 'This Loveone already exists (email/phone). Please verify.']);
        }
        // dd($data);
        $loveone = loveone::create($data);
        $this->createCareteam(Auth::user()->id, $loveone->id, $relationship_id);

        if($request->file){

            $prefix = $data['phone'];
            $photoName = $prefix.'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('loveones/'.$loveone->id.'/'), $photoName);
            $data['photo'] = '/loveones/'.$loveone->id.'/'.$photoName;
            loveone::where('id', $loveone->id)->update(['photo' => $data['photo']]);
        }

        // if ($request->ajax()) 
        return response()->json(['success' => true, 'data' => ['loveone' => $loveone]]);
    }

    /**
     * Shows the edit loveone form
     */
    public function edit(loveone $loveone)
    {
        // TODO: verificar si es el admin del grupo para permitir la edicion del loveone
        // TODO: obtener el careteam Y si es "admin", mostrar esta pagina, sino mostrar una alerta
        // $loveone->load('mycareteam'); 
        $relationships = relationship::where('status', 1)->get();
        $conditions    = condition::where('status', 1)->get();
        return view('loveone.create', compact('relationships', 'conditions', 'loveone'));
    }

    /**
     * Create a careteam
     */
    protected function createCareteam($user_id, $loveone_id, $relationship_id)
    {

        $permissions = [
            'carehub' => 1,
            'lockbox' => 1,
            'medlist' => 1,
            'resources' => 1,
        ];

        $careteam = [
            'loveone_id' => $loveone_id,
            'user_id' => $user_id,
            'relationship_id' => $relationship_id,
            'role' => 'admin',
            'status' => 1,
            'permissions' => serialize($permissions),
        ];
        careteam::create($careteam);
    }
}
