<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.09.2017
 * Time: 19:48
 */

namespace App\Repositories;
use App\Article;


class ArticlesRepository extends Repository
{
    public function __construct(Article $article)
    {
        $this->model = $article;
    }
    


}