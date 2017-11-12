<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Repositories\MenusRepository;
use App\Repositories\PortfoliosRepository;
use Illuminate\Http\Request;

class PortfolioController extends SiteController
{
    //
    public function __construct(PortfoliosRepository $portfoliosRepository)
    {
        parent::__construct(new MenusRepository(new Menu()));
        $this->p_rep = $portfoliosRepository;


        $this->template = env('THEME').'.portfolios';
    }
    public function show($alias = false){

        $portfolio = $this->p_rep->one($alias);

        $this->title = $portfolio->title;
        $this->keywords = $portfolio->keywords;
        $this->meta_desc = $portfolio->meta_desc;

        $portfolios = $this->getPortfolios(config('settings.other_portfolios'), false);

        $content = view(env('THEME').'.portfolio_content')->with(['portfolio' => $portfolio, 'portfolios'=>$portfolios])->render();
        $this->vars = array_add($this->vars,'content', $content);

        return $this->renderOutput();
    }

    /**
     * @return $this
     */
    public function index()
    {
        $this->title = 'Портфолио';
        $this->keywords = 'Портфолио';
        $this->meta_desc = 'Портфолио';

        $portfolios = $this->getPortfolios();
//        dd($portfolios);

        $content = view(env('THEME').'.portfolios_content')->with('portfolios', $portfolios)->render();
        $this->vars = array_add($this->vars, 'content', $content);

        return $this->renderOutput();
    }

    private function getPortfolios($take = false, $paginate = true)
    {
        $portfolios = $this->p_rep->get('*', false, $paginate);
        if ($portfolios){
            $portfolios->load('filter');
        }
        return $portfolios;
    }
}
