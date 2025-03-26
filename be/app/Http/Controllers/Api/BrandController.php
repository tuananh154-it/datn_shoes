<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller {
    public function index() {
        return response()->json(Brand::whereNull('deleted_at')->select('id', 'name')->get());
    }
}
