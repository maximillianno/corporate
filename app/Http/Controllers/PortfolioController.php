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

    /**
     * @return $this
     */
    public function index()
    {
        $this->title = 'Портфолио';
        $this->keywords = 'Портфолио';
        $this->meta_desc = 'Портфолио';

        $portfolios = $this->getPoftfolios();
//        dd($portfolios);

        $content = view(env('THEME').'.portfolios_content')->with('portfolios', $portfolios)->render();
        $this->vars = array_add($this->vars, 'content', $content);

        return $this->renderOutput();
    }

    private function getPoftfolios()
    {
        $portfolios = $this->p_rep->get('*', false, true);
        if ($portfolios){
            $portfolios->load('filter');
        }
        return $portfolios;
    }
}
