<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\UserRequest;
use App\Model\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('user')->only(['edit', 'update', 'create', 'store', 'destroy']);    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withTrashed()->latest()->paginate(env('PERPAGE_COUNT', 50));

        return view('backend.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;

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
            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email')
            ]);

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
}
