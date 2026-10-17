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
        $details = DB::table('buttons_setting')->get();
        $homePageButtons = [];
        foreach ($details as $row) {
            $homePageButtons[$row->page_id][] = [
                'name'  => $row->button_name,
                'icon'  => '<i class="' . $row->button_icon . '"></i>',
                'css'   => $row->class_name,
                'hover' => $row->button_hover,
            ];
        }
        
        View::share('db_buttons', $homePageButtons);
        //echo '<pre>';
        //print_r($homePageButtons);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
