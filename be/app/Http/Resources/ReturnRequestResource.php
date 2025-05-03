<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ReturnRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $user = Auth::user();

        if ($user->role === 'user') {
            return [
                'id' => $this->id,
                'order_id' => $this->order_id,
                'order_total' => $this->order->total_price,
                'order_status' => $this->order->status,
                'bank_account' => $this->bank_account,
                'reason' => $this->reason,
                'requested_at' => optional($this->requested_at)->format('d/m/Y H:i'),
                'approved_at' => optional($this->admin_approved_at)->format('d/m/Y H:i'),
                'staff_notes' => $this->staff_notes,
                'status' => $this->status,
            ];
        }

        if ($user->role === 'staff') {
            return [
                'id' => $this->id,
                'order_id' => $this->order_id,

                'order_total' => $this->order->total_price,
                'order_status' => $this->order->status,
                'customer_name' => $this->user->name,
                'reason' => $this->reason,
                'requested_at' => optional($this->requested_at)->format('d/m/Y H:i'),
                'status' => $this->status,
            ];
        }

        if ($user->role === 'admin') {
            return [
                'id' => $this->id,
                'order_id' => $this->order_id,

                'order_total' => $this->order->total_price,
                'order_status' => $this->order->status,
                'customer_name' => $this->user->name,
                'reason' => $this->reason,
                'description' => $this->description,
                'bank_account' => $this->bank_account,
                'reviewed_by' => optional($this->reviewer)->name,
                'staff_notes' => $this->staff_notes,
                'reviewed_at' => optional($this->reviewed_at)->format('d/m/Y H:i'),
                'status' => $this->status,
            ];
        }

        return [
            'id' => $this->id,
            'customer_name' => $this->user->name,
            'status' => $this->status,
            'message' => 'Thông tin hạn chế với vai trò hiện tại',
        ];
    }
}
