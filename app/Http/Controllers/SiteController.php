<?php

namespace App\Http\Controllers;

use App\Repositories\MenusRepository;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    //repositories
    protected $p_rep;
    protected $s_rep;
    protected $a_rep;
    protected $m_rep;

    protected $template;
    protected $vars = [];

    protected $contentRightBar = false;
    protected $contentLeftBar = false;


    protected $bar = false;

    public function __construct(MenusRepository $menusRepository)
    {
        $this->m_rep = $menusRepository;

    }

    /**
     * returns view with parameters
     * @return $this
     */
    protected function renderOutput(){

        $menu = $this->getMenu();
        dd($menu);
        $navigation = view(env('THEME').'.navigation')->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        return view($this->template)->with($this->vars);
    }

    protected function getMenu()
    {
        $menu = $this->m_rep->get();
        return $menu;
    }


}
