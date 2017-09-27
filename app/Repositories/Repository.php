<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.09.2017
 * Time: 19:49
 */

namespace App\Repositories;
//use Config;


abstract class Repository
{
    protected $model = false;

    public function get(){
        $builder = $this->model->select('*');
//        dd($builder);
        return $builder->get();
    }


}