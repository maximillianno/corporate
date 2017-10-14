<?php

namespace App\Http\Controllers;

use App\Repositories\MenusRepository;
use Illuminate\Http\Request;
use Lavary\Menu\Facade as MenuFacade;


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

//        foreach ($menu->roots() as $item) {
//            if ($item->hasChildren()){
//                dd($item->children());
//            }
//
//        }
//        dd($menu);
        $navigation = view(env('THEME').'.navigation')->with('menu', $menu)->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        return view($this->template)->with($this->vars);
    }

    protected function getMenu()
    {
        $menu = $this->m_rep->get();
//        dd($menu);
        $mBuilder = MenuFacade::make('MyNav', function ($m) use ($menu){
            foreach ($menu as $item) {
//                dd($item);
                if ($item->parent == 0) {
                    $m->add($item->title, $item->path)->id($item->id);
                } else {
                    if ($m->find($item->parent)) {
//                        dd($m->find($item->parent));

                        $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
                    }
                }

            }
        });
        return $mBuilder;
    }


}
