<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Filter;
use App\Http\Requests\MenusRequest;
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
        $this->title = 'Новый пункт меню';
        //корневые
        $tmp = $this->getMenus()->roots();

//        dd($tmp);
        //делаем выпадающий список
        //создаем особенный массив для Form::select
        //TODO разобраться с функцией
        $menus = $tmp->reduce(function ($returnMenus, $menu){

            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;
        },['0' => 'Родительский пункт меню']);

        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();
        $list = [];
        $list = array_add($list, '0', 'Не используется');
        $list = array_add($list, 'parent', 'Раздел Блог');
        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $list[$category->title] = [];

            } else {
                //ищем родительский элемент
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
        //Работа с материалами
        $articles = $this->a_rep->get(['id', 'title', 'alias']);

        $articles = $articles->reduce(function ($returnArticles, $model){
            $returnArticles[$model->alias] = $model->title;
            return $returnArticles;
        },[]);

        //работа с фильтрами
        $filters = Filter::select('id', 'title', 'alias')->get()->reduce(function ($returnFilter, $model){
            $returnFilter[$model->alias] = $model->title;
            return $returnFilter;
        },['parent' => 'Раздел портфолио']);


        //теперь работа с портфолио
        $portfolios = $this->p_rep->get(['id', 'alias', 'title']);
        $portfolios = $portfolios->reduce(function ($returnPortfolios, $model) {
           $returnPortfolios[$model->alias] = $model->title;
           return $returnPortfolios;
        },[]);

        $this->content = view(env('THEME').'.admin.menus_create_content')->with(['menus' => $menus, 'categories' => $list, 'articles' => $articles, 'filters' => $filters, 'portfolios' => $portfolios])->render();

        return $this->renderOutput();



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenusRequest $request)
    {
        //
        $result = $this->m_rep->addMenu($request);
//        dd($result);
        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }
        return redirect('/admin')->with($result);
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

    //получаем список пунктов общего меню Lavary
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
