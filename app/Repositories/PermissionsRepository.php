<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.09.2017
 * Time: 19:48
 */

namespace App\Repositories;
use App\Permission;


class PermissionsRepository extends Repository
{
    protected $role_rep;
    public function __construct(Permission $permission, RolesRepository $rolesRepository)
    {
        $this->role_rep = $rolesRepository;
        $this->model = $permission;
    }

    public function changePermissions($request)
    {
        if (\Gate::denies('change', Permission::class)) {
            abort(403);
        }
        //получаем массив роль - права
        $data = $request->except('_token');


        $roles = $this->role_rep->get();

        foreach ($roles as $role) {

            //передаются права конкретной роли
            if (isset($data[$role->id])) {
                $role->savePermissions($data[$role->id]);
            } else {
                $role->savePermissions([]);
            }
        }
        return ['status' => 'Права обновлены'];

    }


}