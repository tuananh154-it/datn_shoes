<?php
namespace App\Http\Controllers;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // Hiển thị danh sách voucher
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $status = $request->input('status');
    
        $query = Voucher::query();
    
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
    
        if ($status !== null && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }
    
        $vouchers = $query->orderBy('id', 'desc')->paginate(5);
    
        return view('vouchers.index', compact('vouchers'));
    }

    // Hiển thị form tạo voucher mới
   
    public function store(Request $request)
    {
        // Validate dữ liệu từ người dùng
        $request->validate([
            'name' => 'required|string|max:255|unique:vouchers,name',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'expiration_date' => 'required|date|after_or_equal:today', // Sửa lại validate ngày hết hạn
            'min_purchase_amount' => 'required|numeric|min:0',
            'max_discount_amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0', // Thêm validate số lượng
        ], [
            'name.required' => 'Trường này không được để trống.',
            'name.unique' => 'Tên voucher đã tồn tại.',
            'discount_percent.required' => 'Phần trăm giảm giá là bắt buộc.',
            'discount_percent.numeric' => 'Vui lòng nhập số hợp lệ.',
            'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0%.',
            'discount_percent.max' => 'Phần trăm giảm giá không thể lớn hơn 100%.',
            'expiration_date.required' => 'Ngày hết hạn là bắt buộc.',
            'expiration_date.after_or_equal' => 'Ngày hết hạn không được nhỏ hơn ngày hiện tại.',
            'min_purchase_amount.required' => 'Số tiền mua tối thiểu là bắt buộc.',
            'min_purchase_amount.min' => 'Số tiền mua tối thiểu không được âm.',
            'max_discount_amount.required' => 'Mức giảm tối đa là bắt buộc.',
            'max_discount_amount.min' => 'Mức giảm tối đa không được âm.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng không được nhỏ hơn 0.',
        ]);
    
        // Kiểm tra ngày hết hạn và cập nhật trạng thái voucher
        $expirationDate = Carbon::parse($request->expiration_date);
        $status = $expirationDate->isPast() ? 'inactive' : 'active'; // Nếu ngày hết hạn đã qua, đổi trạng thái thành 'inactive'
    
        // Tạo voucher mới
        Voucher::create([
            'name' => $request->name,
            'discount_percent' => $request->discount_percent,
            'expiration_date' => $request->expiration_date,
            'min_purchase_amount' => $request->min_purchase_amount,
            'max_discount_amount' => $request->max_discount_amount,
            'status' => $status, // Cập nhật trạng thái voucher
            'quantity' => $request->quantity,
        ]);
    
        // Chuyển hướng về trang danh sách voucher và thông báo thành công
        return redirect()->route('vouchers.index')->with('success', 'Voucher đã được thêm thành công!');
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

    // Validate dữ liệu từ người dùng
    $request->validate([
        'name' => 'required|string|max:255|unique:vouchers,name,' . $id,
        'discount_percent' => 'required|numeric|min:0|max:100',
        'expiration_date' => 'required|date|after_or_equal:today',  // Cập nhật validate để cho phép ngày hôm nay
        'min_purchase_amount' => 'required|numeric|min:0',
        'max_discount_amount' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0', // Thêm validate số lượng
    ], [
        'name.required' => 'Trường này không được để trống.',
        'name.unique' => 'Tên voucher đã tồn tại.',
        'discount_percent.required' => 'Phần trăm giảm giá là bắt buộc.',
        'discount_percent.numeric' => 'Vui lòng nhập số hợp lệ.',
        'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0%.',
        'discount_percent.max' => 'Phần trăm giảm giá không thể lớn hơn 100%.',
        'expiration_date.required' => 'Ngày hết hạn là bắt buộc.',
        'expiration_date.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày hiện tại.',
        'min_purchase_amount.required' => 'Số tiền mua tối thiểu là bắt buộc.',
        'min_purchase_amount.min' => 'Số tiền mua tối thiểu không được âm.',
        'max_discount_amount.required' => 'Mức giảm tối đa là bắt buộc.',
        'max_discount_amount.min' => 'Mức giảm tối đa không được âm.',
        'quantity.required' => 'Số lượng là bắt buộc.',
        'quantity.integer' => 'Số lượng phải là số nguyên.',
        'quantity.min' => 'Số lượng không được nhỏ hơn 0.',
    ]);

    // Kiểm tra ngày hết hạn và cập nhật trạng thái voucher
    $expirationDate = Carbon::parse($request->expiration_date);
    $status = $expirationDate->isPast() ? 'inactive' : 'active'; // Nếu ngày hết hạn đã qua, đổi trạng thái thành 'inactive'

    // Cập nhật thông tin voucher
    $voucher->update([
        'name' => $request->name,
        'discount_percent' => $request->discount_percent,
        'expiration_date' => $request->expiration_date,
        'min_purchase_amount' => $request->min_purchase_amount,
        'max_discount_amount' => $request->max_discount_amount,
        'status' => $status, // Cập nhật trạng thái voucher
        'quantity' => $request->quantity,
    ]);

    return redirect()->route('vouchers.index')->with('success', 'Voucher đã được cập nhật thành công!');
}


    // Hiển thị chi tiết voucher
    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.show', compact('voucher'));
    }

    // Xóa mềm voucher
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully!');
    }
}
