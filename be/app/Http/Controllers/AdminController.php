<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Hiển thị trang Admin Dashboard
        return view('admin.dashboard');
    }
}
