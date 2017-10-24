<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Repositories\ArticlesRepository;
use App\Repositories\MenusRepository;
use App\Repositories\PortfoliosRepository;
use App\Repositories\SlidersRepository;
use Illuminate\Http\Request;
use Config;

class IndexController extends SiteController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(SlidersRepository $slidersRepository, PortfoliosRepository $portfoliosRepository, ArticlesRepository $articlesRepository)
    {
        parent::__construct(new MenusRepository(new Menu()));
        $this->p_rep = $portfoliosRepository;
        $this->s_rep = $slidersRepository;
        $this->a_rep = $articlesRepository;
        $this->bar = 'right';
        $this->template = env('THEME').'.index';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sliderItems = $this->getSliders();
        $portfolios = $this->getPortfolio();
//        dd($portfolio);
        //шаблоны с переданными переменными, доступными внутри шаблона
        $content = view(env('THEME').'.content')->with('portfolios', $portfolios)->render();
        $sliders = view(env('THEME').'.slider')->with('sliders', $sliderItems)->render();

        $articles = $this->getArticles();
//        dd($articles);
        $this->contentRightBar = view(env('THEME').'.indexBar')->with('articles', $articles)->render();

        $this->vars = array_add($this->vars,'sliders', $sliders);
        $this->vars = array_add($this->vars,'content', $content);
        return $this->renderOutput();
    }


    public function getSliders()
    {
        $sliders = $this->s_rep->get();
        if ($sliders->isEmpty()){
            return false;
        }

        // К слайдеру картинки путь преобразовывает
        $sliders->transform(function ($item, $key){
            $item->img = Config::get('settings.slider_path').'/'.$item->img;
            return $item;
        });
//        dd($sliders);
        return $sliders;
    }

    private function getPortfolio()
    {
        $portfolio = $this->p_rep->get('*', Config::get('settings.home_port_count'));


        return $portfolio;
    }

    private function getArticles()
    {
        $articles = $this->a_rep->get(['title', 'created_at','img', 'alias'], Config::get('settings.home_articles_count'));
        return $articles;
    }
}
