<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreChild;
use App\Model\Amt;
use App\Model\Child;
use App\Utility\Controllers\AmtReplicaTrait;
use Auth;
use DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Log;

class ChildController extends Controller
{
    use AmtReplicaTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $childs = Child::latest()->paginate(env('PERPAGE_COUNT', 50));

        $amts = Amt::all();

        return view('backend/child/index', compact('childs', 'amts'));
    }

    public function report(Child $child, AmtAlsReport $replica)
    {
        return view('backend/child/report', compact('child', 'replica'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend/child/create', ['child' => new Child]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreChild  $StoreChild
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChild $storeChild)
    {
        DB::beginTransaction();
        try  {
            $user = Auth::user();

            $child = Child::create([
                'name' => $storeChild->get('name'),
                'sex' => $storeChild->get('sex'),
                'birthday' => $storeChild->get('birthday')
            ]);

            $user->childs()->attach($child);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect("/backend/child")->with('error', "{$e->getMessage()}");
        }

        DB::beginTransaction();
        try {
            return $this->replicaFlow($user, $child, Amt::find(Amt::DEFAULT_AMT_ID));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect('backend/child')->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Child $child
     * @return \Illuminate\Http\Response
     */
    public function show(Child $child)
    {
        return view('backend/child/show', compact('child'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Child $child
     * @return \Illuminate\Http\Response
     */
    public function edit(Child $child)
    {
        return view('backend/child/edit', compact('child'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreChild  $StoreChild
     * @param  \App\Model\Child $child 
     * @return \Illuminate\Http\Response
     */
    public function update(StoreChild $storeChild, Child $child)
    {
        $child->update([
            'name' => $storeChild->get('name'),
            'sex' => $storeChild->get('sex'),
            'birthday' => $storeChild->get('birthday')
        ]);
        
        return redirect("/backend/child")->with('success', "{$child->name}{$child->getSex()} 更新完成!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
