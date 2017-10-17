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

    public function get($select = '*', $take = false){
        $builder = $this->model->select($select);
        if ($take) {
            $builder->take($take);
        }
//        dd($builder);
        return $this->check($builder->get());
    }

    protected function check($result) {
        if ($result->isEmpty()) {
            return false;
        }
        //Все картинки декодирует
        $result->transform(function ($item, $key) {
            if (is_string($item->img) && is_object(json_decode($item->img)) && json_last_error() == JSON_ERROR_NONE) {

                $item->img = json_decode($item->img);
            }
            return $item;
        });
        return $result;

    }


}