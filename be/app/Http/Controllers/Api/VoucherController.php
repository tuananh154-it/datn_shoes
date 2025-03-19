<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller {
    public function index() {
        return response()->json(Voucher::where('status', 'active')->whereNull('deleted_at')->select('id', 'name', 'discount_amount', 'discount_percent', 'expiration_date', 'min_purchase_amount', 'max_discount_amount', 'status')->get());
    }
    
    public function show($id) {
        return response()->json(Voucher::select('id', 'name', 'discount_amount', 'discount_percent', 'expiration_date', 'min_purchase_amount', 'max_discount_amount', 'status')->findOrFail($id));
    }
}