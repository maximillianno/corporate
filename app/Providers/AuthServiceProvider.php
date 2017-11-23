<?php

namespace App\Providers;

use App\Article;
use App\Policies\ArticlePolicy;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Article::class => ArticlePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //первое VIEW_ADMIN - правило проверки авторизации, а передаваемое - пермишн в базе данных
        \Gate::define('VIEW_ADMIN', function($user){
            return $user->canDo('VIEW_ADMIN');
        });

        //поставил User перед $user для перехода на canDo()
        \Gate::define('VIEW_ADMIN_ARTICLES', function(User $user){
            return $user->canDo('VIEW_ADMIN_ARTICLES');
        });



        //
    }
}
