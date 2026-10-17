<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            $view->with('db_buttons', app('app.view_db_buttons'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('app.view_db_buttons', function () {
            $details = DB::table('buttons_setting')->get();
            $homePageButtons = [];

            foreach ($details as $row) {
                $homePageButtons[$row->page_id][] = [
                    'name' => $row->button_name,
                    'icon' => '<i class="' . $row->button_icon . '"></i>',
                    'css' => $row->class_name,
                    'hover' => $row->button_hover,
                ];
            }

            return $homePageButtons;
        });
    }
}
