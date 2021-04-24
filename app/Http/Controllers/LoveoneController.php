<?php

namespace App\Http\Controllers;

use App\Models\loveone;
use App\Models\careteam;
use App\Models\condition;
use App\Models\relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Creates a loveone register
     */
    public function createUpdate(CreateLoveoneRequest $request)
    {
        $data = $request->all();
        $data['condition_ids'] = implode(',', $request->condition_ids);
        $relationship_id = $data['relationship_id'];
        $data['phone'] = intval($data['phone']);
        
        unset($data['_token']);
        unset($data['relationship_id']);

        // edit
        if($request->id > 0)
            $loveone = loveone::update($data);
        // create
        else{
            $loveone = loveone::create($data);
            $this->createCareteam(Auth::user()->id, $loveone->id, $relationship_id);
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
