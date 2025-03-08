<?php
namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // Hiển thị danh sách voucher
    public function index()
    {
        $vouchers = Voucher::all();  // Lấy tất cả các voucher, bao gồm cả đã bị xóa mềm
        return view('vouchers.index', compact('vouchers'));
    }

    // Hiển thị form tạo voucher mới
    public function create()
    {
        return view('vouchers.create');
    }

    // Lưu voucher mới
    public function store(Request $request)
    {
        $request->validate([
            'discount_amount' => 'nullable|numeric',
            'discount_percent' => 'nullable|numeric',
            'expiration_date' => 'required|date',
            'min_purchase_amount' => 'nullable|numeric',
            'max_discount_amount' => 'nullable|numeric',
            'terms_and_conditions' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Voucher::create($request->all());
        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully!');
    }

    // Hiển thị form sửa voucher
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.edit', compact('voucher'));
    }

    // Cập nhật voucher
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        $request->validate([
            'discount_amount' => 'nullable|numeric',
            'discount_percent' => 'nullable|numeric',
            'expiration_date' => 'required|date',
            'min_purchase_amount' => 'nullable|numeric',
            'max_discount_amount' => 'nullable|numeric',
            'terms_and_conditions' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $voucher->update($request->all());
        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully!');
    }

    // Xóa mềm voucher
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();  // Xóa mềm
        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully!');
    }
}
