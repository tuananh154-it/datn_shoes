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
        'name' => 'required|string|max:255',
        'discount_amount' => 'required|numeric|min:0|lte:min_purchase_amount|lte:max_discount_amount',
        'discount_percent' => 'required|numeric|min:0|max:100',
        'expiration_date' => 'required|date|after:today',
        'min_purchase_amount' => 'required|numeric|min:0',
        'max_discount_amount' => 'required|numeric|min:0|gte:discount_amount',
    ], [
        'required' => 'Trường này không được để trống.',
        'numeric' => 'Vui lòng nhập số hợp lệ.',
        'min' => 'Giá trị phải lớn hơn hoặc bằng 0.',
        'max' => 'Phần trăm giảm giá không thể lớn hơn 100%.',
        'lte' => 'Số tiền giảm giá phải nhỏ hơn hoặc bằng số tiền mua tối thiểu và mức giảm tối đa.',
        'gte' => 'Mức giảm tối đa phải lớn hơn hoặc bằng số tiền giảm giá.',
        'after' => 'Ngày hết hạn phải lớn hơn ngày hiện tại.',
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
        'name' => 'required|string|max:255',
        'discount_amount' => 'required|numeric|min:0|lte:min_purchase_amount|lte:max_discount_amount',
        'discount_percent' => 'required|numeric|min:0|max:100',
        'expiration_date' => 'required|date',
        'min_purchase_amount' => 'required|numeric|min:0',
        'max_discount_amount' => 'required|numeric|min:0|gte:discount_amount',
    ], [
        'required' => 'Trường này không được để trống.',
        'numeric' => 'Vui lòng nhập số hợp lệ.',
        'min' => 'Giá trị phải lớn hơn hoặc bằng 0.',
        'max' => 'Phần trăm giảm giá không thể lớn hơn 100%.',
        'lte' => 'Số tiền giảm giá phải nhỏ hơn hoặc bằng số tiền mua tối thiểu và mức giảm tối đa.',
        'gte' => 'Mức giảm tối đa phải lớn hơn hoặc bằng số tiền giảm giá.',
        'after' => 'Ngày hết hạn phải lớn hơn ngày hiện tại.',
    ]);

        $voucher->update($request->all());
        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully!');
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