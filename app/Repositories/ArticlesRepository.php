<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.09.2017
 * Time: 19:48
 */

namespace App\Repositories;
use App\Article;
use App\Http\Requests\ArticleRequest;
use Image;


class ArticlesRepository extends Repository
{
    public function __construct(Article $article)
    {
        $this->model = $article;
    }


    /**
     * @param $alias
     * @param array $attr для подгрузки комментов
     * @return mixed
     */
    public function one($alias, $attr = [])
    {
        $article = parent::one($alias,$attr);
        if ($article && !empty($attr)){
            $article->load('comments');
            $article->comments->load('user');

        }
        return $article;

    }

    public function addArticle(ArticleRequest $request)
    {
        if (\Gate::denies('save',Article::class)) {
            abort(403);
        }

        //данные POST
        $data = $request->except('_token', 'image');
        if (empty($data)){
            return ['error' => 'Нет данных'];
        }
        //add alias
        if (empty($data['alias'])) {
            $data['alias'] = $this->transliterate($data['title']);
        }
        if ($this->one($data['alias'],false)) {
            $request->merge(['alias' => $data['alias']]);
            $request->flash();
            return ['error' => 'Данный псевдоним уже используется'];
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->isValid()) {
                //рандомная строка
                $str = str_random(8);
                // хз новый объект
                $obj = new \stdClass();

                $obj->mini = $str . 'mini.jpg';
                $obj->max = $str . 'maxi.jpg';
                $obj->path = $str . 'path.jpg';

                $img = Image::make($image);
                $img->fit(\Config::get('settings.image.width'), \Config::get('settings.image.height'))->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->path);
                $img->fit(\Config::get('settings.articles_img.max.width'), \Config::get('settings.articles_img.max.height'))->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->max);
                $img->fit(\Config::get('settings.articles_img.mini.width'), \Config::get('settings.articles_img.mini.height'))->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->mini);

                // объект в строку
                $data['img'] = json_encode($obj);
                // заполняем модель данными
                $this->model->fill($data);

                if ($request->user()->articles()->save($this->model)) {
                    return ['status' => 'Материал добавлен'];

                }


            }
        }
    }




}