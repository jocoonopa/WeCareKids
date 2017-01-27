<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UserRequest;
use App\Model\Organization;
use App\Model\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('can:view,user')->only('show');
        $this->middleware('can:update,user')->only('edit', 'update', 'reset', 'showResetForm');
        $this->middleware('can:create,' . \App\Model\User::class)->only('create', 'store');
        $this->middleware('can:delete,user')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::with('cxts', 'replicas', 'organization')->fetchUsersInValidScope(Auth::user());
        
        if (0 !== (int) $request->get('organization_id', 0) && Auth::user()->isSuper()) {
            $query->where('organization_id', $request->get('organization_id'));
        }

        $users = $query->paginate(env('PERPAGE_COUNT', 50));

        return view('backend.user.index', compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Model\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('backend/user/show', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;

        $user->name = old('name');
        $user->email = old('email');
        $user->phone = old('phone');
        $user->organization_id = old('organization_id');

        return view('backend/user/create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $user = User::_create($request->all());

            return redirect('/backend/user')->with('success', "{$user->name} 新增完成!");    
        } catch (\Exception $e) {
            return redirect('/backend/user/create')->with('error', "{$e->getMessage()}")->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Model\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('backend/user/edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @param  App\Model\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        try {             
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->phone = $request->get('phone');
            $user->organization()->associate(Organization::find($request->get('organization_id')));
            $user->save();

            return redirect("/backend/user")->with('success', "{$user->name} 更新完成!");  
        } catch (\Exception $e) {
            return redirect("/backend/user/{$user->id}/edit")->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Model\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->back()->with('success', "{$user->name} 已經成功停用!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "{$e->getMessage()}");
        }        
    }

    /**
     * Restore the specified resource from storage.
     * 
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        try {
            $user = User::withTrashed()->where('id', $id)->first(); 
            $user->restore();

            return redirect()->back()->with('success', "{$user->name} 已經成功啟用!");    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "{$e->getMessage()}");
        }
    }

    public function showResetForm(User $user)
    {
        return view('backend/user/reset', compact('user'));
    }

    public function reset(PasswordRequest $request, User $user)
    {
        try {
            $user->password = bcrypt($request->get('password'));
            $user->save();

            return redirect()->back()->with('success', "{$user->name} 密碼修改完成!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "{$e->getMessage()}");   
        }
    }
}
