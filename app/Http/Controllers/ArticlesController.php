<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Menu;
use App\Repositories\ArticlesRepository;
use App\Repositories\CommentsRepository;
use App\Repositories\MenusRepository;
use App\Repositories\PortfoliosRepository;
use Illuminate\Http\Request;


class ArticlesController extends SiteController
{
    //
    private $c_rep;

    public function __construct(PortfoliosRepository $portfoliosRepository, ArticlesRepository $articlesRepository, CommentsRepository $commentsRepository)
    {
        parent::__construct(new MenusRepository(new Menu()));
        $this->p_rep = $portfoliosRepository;
        $this->a_rep = $articlesRepository;
        $this->c_rep = $commentsRepository;

        $this->bar = 'right';
        $this->template = env('THEME').'.articles';
    }
    public function create() {
        dd($this);
    }

//    public function show(Article $article, $alias = false){
  public function show($alias = false){
        // пока оставим так, теперь в переменную алиас передается выбор модели по алиасу
        $article = $this->a_rep->one($alias,['comments'=>true]);

        if ($article) {
            $article->img = json_decode($article->img);
        }

//        dd($article->comments->groupBy('parent_id'));
        $this->title = $article->title;
        $this->keywords = $article->keywords;
        $this->meta_desc = $article->meta_desc;

        $content = view(env('THEME').'.article_content')->with('article', $article)->render();
        $this->vars = array_add($this->vars,'content', $content);

        $comments = $this->getComments(config('settings.recent_comments'));
        $portfolios = $this->getPortfolios(config('settings.recent_portfolios'));

        $this->contentRightBar = view(env('THEME').'.articlesBar')->with(['comments'=>$comments, 'portfolios' => $portfolios]);
        return $this->renderOutput();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($cat_alias = false)
    {
        $this->title = 'Блог';
        $this->keywords = 'String';
        $this->meta_desc = 'String';

        $articles = $this->getArticles($cat_alias);
        $content = view(env('THEME').'.articles_content')->with('articles', $articles);
        $this->vars = array_add($this->vars, 'content', $content);
        $comments = $this->getComments(config('settings.recent_comments'));
        $portfolios = $this->getPortfolios(config('settings.recent_portfolios'));

        $this->contentRightBar = view(env('THEME').'.articlesBar')->with(['comments'=>$comments, 'portfolios' => $portfolios]);
        return $this->renderOutput();
    }

    private function getArticles($alias = false)
    {
        $where = false;
        if ($alias) {
            $id = Category::select('id')->where('alias', $alias)->first()->id;
            $where = ['category_id',$id];
        }
        $articles = $this->a_rep->get(['id', 'title', 'alias', 'created_at', 'img', 'desc','user_id', 'category_id', 'keywords', 'meta_desc'], false, true, $where);
        if ($articles) {
            $articles->load('user','category','comments');
        }
        return $articles;
//        dd($articles);
    }

    private function getComments($take)
    {
        $comments = $this->c_rep->get(['text', 'name', 'email', 'site', 'article_id', 'user_id'], $take);
        if ($comments) {
            $comments->load('article','user');
        }
        return $comments;
    }

    private function getPortfolios($take)
    {
        $portfolios = $this->p_rep->get(['title', 'text' , 'alias', 'customer', 'img', 'filter_alias'], $take);
        return $portfolios;
    }

}
