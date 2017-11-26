<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.09.2017
 * Time: 19:48
 */

namespace App\Repositories;
use App\Menu;


class MenusRepository extends Repository
{
    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }

    public function addMenu($request)
    {
        if (\Gate::denies('save', Menu::class)) {
            abort(403);
        }
        $data = $request->only('title', 'type', 'parent');

        if (empty($data)) {
            return ['error' => 'Нет данных'];
        }
//        dd($request->all());
        //записываем путь в массив если это пользовательская
        switch ($data['type']){
            case 'customLink': $data['path'] = $request->input('custom_link');
                break;
            case 'blogLink' :
                if ($request->input('category_alias')) {
                    if ($request->input('category_alias') == 'parent'){
                        $data['path'] = route('articles.index');
                    } else {
                        $data['path'] = route('articlesCat', ['cat_alias' => $request->input('category_alias')]);

                    }

                }
                elseif ($request->input('article_alias')) {
                    $data['path'] = route('articles.show', ['alias' => $request->input('article_alias')]);
                }
                break;
            case 'portfolioLink':
                if ($request->input('filter_alias')) {
                    if ($request->input('filter_alias') == 'parent') {
                        $data['path'] = route('portfolios.index');
                    }

                } elseif ($request->input('portfolio_alias')) {
                     $data['path'] = route('portfolios.show', ['alias' => $request->input('portfolio_alias')]);

                }
                break;

        }
        unset($data['type']);
//        dd($this->model->fill($data));
        if ($this->model->fill($data)->save()) {
            return ['status' => 'Ссылка добавлена'];
        }

    }


}