<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Repositories\ArticlesRepository;
use App\Repositories\MenusRepository;
use App\Repositories\PortfoliosRepository;
use Illuminate\Http\Request;

class ArticlesController extends SiteController
{
    //
    public function __construct(PortfoliosRepository $portfoliosRepository, ArticlesRepository $articlesRepository)
    {
        parent::__construct(new MenusRepository(new Menu()));
        $this->p_rep = $portfoliosRepository;
        $this->a_rep = $articlesRepository;

        $this->bar = 'right';
        $this->template = env('THEME').'.articles';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = $this->getArticles();
        $content = view(env('THEME').'.articles_content')->with('articles', $articles);
        $this->vars = array_add($this->vars, 'content', $content);
        return $this->renderOutput();
    }

    private function getArticles($alias = false)
    {
        $articles = $this->a_rep->get(['id', 'title', 'alias', 'created_at', 'img', 'desc','user_id', 'category_id'], false, true);
//        if ($articles) {
//            $articles->load('user','category','comments');
//        }
        return $articles;
//        dd($articles);
    }

}
