<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use Illuminate\Contracts\Support\Renderable;
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

    /**
     * Show the application dashboard.
     *
     * @param UserDataTable $table
     * @return Renderable
     */
    public function index(UserDataTable $table)
    {
        return $table->render('home');
    }

    public function home()
    {
        return view('welcome');
    }
}
