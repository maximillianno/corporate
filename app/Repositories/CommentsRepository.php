<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.09.2017
 * Time: 19:48
 */

namespace App\Repositories;
use App\Comment;



class CommentsRepository extends Repository
{
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }


}