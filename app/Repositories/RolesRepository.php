<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.09.2017
 * Time: 19:48
 */

namespace App\Repositories;
use App\Role;


class RolesRepository extends Repository
{
    public function __construct(Role $role)
    {
        $this->model = $role;
    }


}