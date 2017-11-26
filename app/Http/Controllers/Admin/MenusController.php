<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ArticlesRepository;
use App\Repositories\MenusRepository;
use App\Repositories\PortfoliosRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenusController extends AdminController
{
    protected $m_rep;

    public function __construct(MenusRepository $menusRepository, ArticlesRepository $articlesRepository, PortfoliosRepository $portfoliosRepository)
    {
        parent::__construct();
        $this->m_rep = $menusRepository;
        $this->a_rep = $articlesRepository;
        $this->p_rep = $portfoliosRepository;

        $this->template = env('THEME').'.admin.menus';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (\Gate::denies('VIEW_ADMIN_MENU')) {
            abort(403);
        }
        $menu = $this->getMenus();
        $this->content = view(env('THEME').'.admin.menus_content')->with('menus',  $menu);
        return $this->renderOutput();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getMenus()
    {
        $menu = $this->m_rep->get();
        if ($menu->isEmpty()){
            return false;
        }
        return \Menu::make('forMenuPart', function ($m) use ($menu) {
            foreach ($menu as $item) {
                if ($item->parent == 0) {
                    $m->add($item->title, $item->path)->id($item->id);
                } elseif ($m->find($item->parent)) {
                    $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
                }

            }
        });
    }
}
