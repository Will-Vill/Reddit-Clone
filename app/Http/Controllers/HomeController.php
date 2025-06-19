<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (!session('id')) {
            return redirect()->route('login');
        }
        return view('index');
    }
}