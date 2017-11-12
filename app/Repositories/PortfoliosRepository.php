<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.09.2017
 * Time: 19:48
 */

namespace App\Repositories;
use App\Portfolio;


class PortfoliosRepository extends Repository
{
    public function __construct(Portfolio $portfolio)
    {
        $this->model = $portfolio;
    }
    public function one($alias, $attr = []){
        $portfolio = parent::one($alias);
        if ($portfolio && $portfolio->img) {
            $portfolio->img = json_decode($portfolio->img);
        }
        return $portfolio;

    }


}