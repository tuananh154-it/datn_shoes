<?php
namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // Hiển thị danh sách voucher
    public function index(Request $request)
    {
        // Lấy các tham số tìm kiếm từ form
        $searchTerm = $request->input('search');
        $status = $request->input('status');
    
        $query = Voucher::query();
    
        // Tìm kiếm theo tên nếu có
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
    
        // Lọc theo trạng thái nếu có
        if ($status !== null && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }
    
        // Lấy danh sách voucher sau khi lọc và phân trang
        $vouchers = $query->orderBy('id', 'desc')->paginate(5);
    
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
            'name' => 'required|string|max:255|unique:vouchers,name',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'expiration_date' => 'required|date|after:today',
            'min_purchase_amount' => 'required|numeric|min:0',
            'max_discount_amount' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Trường này không được để trống.',
            'name.unique' => 'Tên voucher đã tồn tại.',
            'discount_percent.required' => 'Phần trăm giảm giá là bắt buộc.',
            'discount_percent.numeric' => 'Vui lòng nhập số hợp lệ.',
            'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0%.',
            'discount_percent.max' => 'Phần trăm giảm giá không thể lớn hơn 100%.',
            'expiration_date.required' => 'Ngày hết hạn là bắt buộc.',
            'expiration_date.after' => 'Ngày hết hạn phải lớn hơn ngày hiện tại.',
            'min_purchase_amount.required' => 'Số tiền mua tối thiểu là bắt buộc.',
            'min_purchase_amount.min' => 'Số tiền mua tối thiểu không được âm.',
            'max_discount_amount.required' => 'Mức giảm tối đa là bắt buộc.',
            'max_discount_amount.min' => 'Mức giảm tối đa không được âm.',
        ]);
    
        Voucher::create($request->all());
    
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
    
        $request->validate([
            'name' => 'required|string|max:255|unique:vouchers,name,' . $id,
            'discount_percent' => 'required|numeric|min:0|max:100',
            'expiration_date' => 'required|date|after:today',
            'min_purchase_amount' => 'required|numeric|min:0',
            'max_discount_amount' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Trường này không được để trống.',
            'name.unique' => 'Tên voucher đã tồn tại.',
            'discount_percent.required' => 'Phần trăm giảm giá là bắt buộc.',
            'discount_percent.numeric' => 'Vui lòng nhập số hợp lệ.',
            'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0%.',
            'discount_percent.max' => 'Phần trăm giảm giá không thể lớn hơn 100%.',
            'expiration_date.required' => 'Ngày hết hạn là bắt buộc.',
            'expiration_date.after' => 'Ngày hết hạn phải lớn hơn ngày hiện tại.',
            'min_purchase_amount.required' => 'Số tiền mua tối thiểu là bắt buộc.',
            'min_purchase_amount.min' => 'Số tiền mua tối thiểu không được âm.',
            'max_discount_amount.required' => 'Mức giảm tối đa là bắt buộc.',
            'max_discount_amount.min' => 'Mức giảm tối đa không được âm.',
        ]);
    
        $voucher->update($request->all());
    
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
        $voucher->delete();  // Xóa mềm
        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully!');
    }
}