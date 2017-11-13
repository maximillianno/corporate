<?php

namespace App\Http\Controllers\Admin;

use Menu;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    //
    protected $p_rep;
    protected $a_rep;
    protected $user;
    protected $template;
    protected $content = false;
    protected $title;
    protected $vars;

    public function __construct()
    {
        $this->user = Auth::user();


//        if (!$this->user) {
//            abort(404);
//        }
    }
    public function renderOutput() {
        $this->vars = array_add($this->vars, 'title', $this->title);
        $menu = $this->getMenu();
        $navigation = view(env('THEME').'.admin.navigation')->with('menu', $menu)->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        if ($this->content) {
            $this->vars = array_add($this->vars,'content', $this->content);
        }
        $footer = view(env('THEME').'.admin.footer')->render();
        $this->vars = array_add($this->vars,'footer', $footer);
        return view($this->template)->with($this->vars);

    }

    public function getMenu()
    {
        return Menu::make('adminMenu', function ($menu){
           $menu->add('Статьи', array('route'=>'articles.index'));
           $menu->add('Портфолио', array('route'=>'articles.index'));
           $menu->add('Меню', array('route'=>'articles.index'));
           $menu->add('Пользователи', array('route'=>'articles.index'));
           $menu->add('Привелегии', array('route'=>'articles.index'));
        });
    }
}
