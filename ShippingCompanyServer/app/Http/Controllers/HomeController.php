<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    function console_log($data){
        echo '<script>';
        echo 'console.log('. json_encode($data) .')';
        echo '</script>';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = \Auth::user();

        switch(true)
        {
            case $user->isDispatcher():
                $this->console_log($user);
                return view('dispatcher/home');
            case $user->isClient():
                return view('client/home');
            default:
                return view('home');
        }
    }
}
