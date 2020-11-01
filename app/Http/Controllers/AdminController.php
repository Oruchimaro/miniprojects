<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin-home');
    }

    /**
     * This logout is for admin that is also logged in as  a user
     * and after logging out doesnt want logout of user too.
     *
     * @return void
     */
    public function softLogout()
    {
        auth()->guard('admin')->logout();
        return redirect('/home');
    }
}
