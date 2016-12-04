<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\AmtDiagGroup;
use App\Model\AmtReplica;
use App\Model\AmtReplicaDiag;
use App\Model\AmtReplicaDiagGroup;
use Auth;

class AmtReplicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replicas = AmtReplica::all();

        return view('/backend/amt_replica/index', compact('replicas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/backend/amt_replica/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $replica = AmtReplica::create([
            'amt_id' => 1,
            'creater_id' => Auth::user()->id,
            'child_id' => 1,
            'status' => 0
        ]);

        $groups = AmtDiagGroup::findValid()->each(function ($group) use ($replica) {
            $replicaGroup = AmtReplicaDiagGroup::create([
                'replica_id' => $replica->id,
                'group_id' => $group->id
            ]);

            echo "<hr>replica group:  {$replicaGroup}<hr>";

            $group->diags()->get()->each(function ($diag) use ($replicaGroup) {
                $replicaDiag = AmtReplicaDiag::create([
                    'diag_id' => $diag->id,
                    'group_id' => $replicaGroup->id
                ]);

                echo "replicaDiag:  {$replicaDiag->id}<br>";
            });
        });

        return __METHOD__;
    }

    public function destroy(AmtReplica $replica)
    {
        $replica->delete();

        return redirect('/backend/amt_replica')->with('success', "{$replica->id}刪除完成!");
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Model\AmtReplica  $replica
     * @return \Illuminate\Http\Response
     */
    public function show(AmtReplica $replica)
    {
        return $replica->id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Model\AmtReplica $replica
     * @return \Illuminate\Http\Response
     */
    public function edit(AmtReplica $replica)
    {
        return $replica->id;
    }
}
