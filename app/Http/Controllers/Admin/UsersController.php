<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends AdminController
{
    protected $us_rep;
    protected $rol_rep;

    public function __construct(RolesRepository $rolesRepository, UsersRepository $usersRepository)
    {
        parent::__construct();
        $this->us_rep = $usersRepository;
        $this->rol_rep = $rolesRepository;

        $this->template = env('THEME').'.admin.users';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //все пользователи
        $users = $this->us_rep->get();
        $this->content = view(env('THEME').'.admin.users_content')->with(['users' => $users])->render();
        return $this->renderOutput();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $this->title = 'Новый пользователь';
//        $users = $this->us_rep->get();
        $roles = $this->rol_rep->get(['id', 'name']);
        $roles = $roles->reduce(function ($returnRole, $model){
           $returnRole[$model->id] = $model->name;
           return $returnRole;
        });
//        dd($roles);
        $this->content = view(env('THEME').'.admin.users_create_content')->with([ 'roles' => $roles])->render();
        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //
        $result = $this->us_rep->addUser($request);
//        dd($result);
        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }
        return redirect('/admin')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        $this->title = 'Редактирование пользователя '. $user->name;

        $roles = $this->rol_rep->get()->reduce(function ($returnRole, $model){
            $returnRole[$model->id] = $model->name;
            return $returnRole;
        });
        $this->content = view(env('THEME').'.admin.users_create_content')->with(['roles' => $roles, 'user' => $user])->render();
        return $this->renderOutput();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        //

        $result = $this->us_rep->updateUser($request, $user);
//        dd($result);
        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }
        return redirect('/admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        $result = $this->us_rep->deleteUser($user);

        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }
        return redirect('/admin')->with($result);



    }
}
