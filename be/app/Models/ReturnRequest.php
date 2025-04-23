<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_detail_id',
        'user_id',
        'reason',
        'description',
        'image',
        'status',
        'bank_account',
        'reviewed_by',
        'admin_id',
        'requested_at',
        'reviewed_at',
        'admin_approved_at',
        'return_received_at',
        'staff_notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'admin_approved_at' => 'datetime',
        'return_received_at' => 'datetime',
    ];

    // Quan hệ người gửi yêu cầu (khách hàng)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ tới đơn hàng liên quan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }


    // Nhân viên CSKH xem xét đơn
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Admin duyệt đơn hoàn
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
