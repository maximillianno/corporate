<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Category;
use App\Http\Requests\ArticleRequest;
use App\Repositories\ArticlesRepository;
use Illuminate\Http\Request;


class ArticlesController extends AdminController
{
    public function __construct(ArticlesRepository $a_rep)
    {
        parent::__construct();
        $this->a_rep = $a_rep;

        $this->template = env('THEME').'.admin.articles';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (\Gate::denies('VIEW_ADMIN_ARTICLES')) {
            abort(403);
        }
        $this->title = 'Менеджер статей';
        $articles = $this->getArticles();
        $this->content = view(env('THEME').'.admin.articles_content')->with('articles', $articles)->render();


        return $this->renderOutput();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if (\Gate::denies('save', Article::class)) {
            abort(403);
        }

//        dd($request->route());
//        dd($GLOBALS);

        $this->title = 'Добавить новый материал';
        $categories = Category::select(['title','alias', 'parent_id', 'id'])->get();
        $lists = [];
        foreach ($categories as $category) {
            if ($category->parent_id  == 0) {
                $lists[$category->title] = [];
            } else {
                $lists[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->content = view(env('THEME').'.admin.articles_create_content')->with('categories', $lists)->render();
        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        //
//        dd($request);
        $result = $this->a_rep->addArticle($request);
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
    public function edit(Article $article)
    {
        //$article = Article::where('alias', $alias);
        if (\Gate::denies('update', $article)){
            abort(403);
        }
        $article->img = json_decode($article->img);
        $categories = Category::select(['title','alias', 'parent_id', 'id'])->get();
        $lists = [];
        foreach ($categories as $category) {
            if ($category->parent_id  == 0) {
                $lists[$category->title] = [];
            } else {
                $lists[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->title = 'Редактирование материала - '. $article->title;

        $this->content = view(env('THEME').'.admin.articles_create_content')->with(['categories' => $lists, 'article' => $article])->render();
        return $this->renderOutput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        //
//        dd($request);
        $result = $this->a_rep->updateArticle($request, $article);
//        dd($result);
        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }
        return redirect('/admin')->with($result);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
        $result = $this->a_rep->deleteArticle($article);

        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }
        return redirect('/admin')->with($result);
    }

    private function getArticles()
    {
        return $this->a_rep->get();
    }
}
