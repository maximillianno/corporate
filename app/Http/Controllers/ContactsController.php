<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
use App\Menu;
use App\Repositories\MenusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class ContactsController extends SiteController
{
    //
    public function __construct()
    {
        parent::__construct(new MenusRepository(new Menu()));

        $this->bar = 'left';
        $this->template = env('THEME').'.contacts';
    }
    public function index(Request $request) {
        if ($request->isMethod('post')) {
            $messages = [
                'required' => 'Поле :attribute Обязательно к заполнению',
                'email' => 'Поле :attribute должно содержать правильный email'
            ];
            $this->validate($request, [
               'name' => 'required|max:255',
               'email' => 'required|email',
               'text' => 'required'
            ], $messages);

            $data = $request->all();

//            $result = Mail::to(env('MAIL_ADMIN'))->send(new OrderShipped($data));

            $result = Mail::send(env('THEME').'.email', ['data' => $data], function ($m) use ($data){
                $mail_admin = env('MAIL_ADMIN');
                $m->from($data['email'], $data['name']);
                $m->to($mail_admin, 'Mr. Admin')->subject('Question');

            });
//                dd($result);
                return redirect()->route('contacts')->with('status', 'email is sent');




        }

        $this->title = 'Контакты';
        $content = view(env('THEME').'.contact_content')->render();
        $this->contentLeftBar = view(env('THEME').'.contact_bar')->render();
        $this->vars = array_add($this->vars, 'content', $content);
        return $this->renderOutput();
    }

}
