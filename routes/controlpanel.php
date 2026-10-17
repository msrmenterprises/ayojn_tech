<?php

use App\User;


Route::get('/', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('controlpanel')->user();
    $offercount=User::where('sponsor_type',1)->where('id','!=',Auth::user()->id)->count();
    $managecount=User::where('sponsor_type',2)->where('id','!=',Auth::user()->id)->count();
    
    //dd($users);

    return view('controlpanel.home',compact('offercount','managecount'));
})->name('home');

