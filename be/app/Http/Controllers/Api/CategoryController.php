<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller {
    public function index() {
        return response()->json(Category::whereNull('deleted_at')->select('id', 'name')->get());
    }
    
   
}
