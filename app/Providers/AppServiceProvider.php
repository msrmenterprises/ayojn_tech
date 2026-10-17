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
        $publicPath = config('app.public_path');

        if ($publicPath !== null && $publicPath !== '') {
            $resolvedPublicPath = realpath($publicPath);

            if ($resolvedPublicPath === false || !is_dir($resolvedPublicPath)) {
                throw new \InvalidArgumentException('APP_PUBLIC_PATH must point to an existing directory.');
            }

            $this->app->instance('path.public', $resolvedPublicPath);
        }

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
