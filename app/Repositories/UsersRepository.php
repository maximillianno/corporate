<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.09.2017
 * Time: 19:48
 */

namespace App\Repositories;
use App\User;


class UsersRepository extends Repository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function addUser($request)
    {
        if (\Gate::denies('create', User::class)) {
            abort(403);
        }
        $data = $request->all();
//        dd($data);
        $user = User::create([
            'name' => $data['name'],
            'login' => $data['login'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        if ($user) {
            $user->roles()->attach($data['role_id']);
        }
        return ['status' => 'Пользователь добавлен'];


    }

    public function updateUser($request, $user)
    {
        if (\Gate::denies('edit', $this->model)) {
            abort(403);
        }
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

//        dd($data);

        $user->fill($data)->update();
        $user->roles()->sync([$data['role_id']]);
        return ['status' => 'Пользователь изменен'];
    }

    public function deleteUser($user)
    {
        if (\Gate::denies('edit', $this->model)) {
            abort(403);
        }

        $user->roles()->detach();

        if ($user->delete()) {
            return ['status' => 'Пользователь удален'];
        }

    }


}