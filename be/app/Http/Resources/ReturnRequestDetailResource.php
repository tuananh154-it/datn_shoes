<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ReturnRequestDetailResource extends JsonResource
{
    protected $orderDetails;

    public function __construct($resource, $orderDetails = [])
    {
        parent::__construct($resource);
        $this->orderDetails = collect($orderDetails);
    }

    public function toArray(Request $request): array
    {
        $user = Auth::user();

        $products = $this->orderDetails->map(function ($detail) {
            $productDetail = $detail->productDetail;
            $product = optional($productDetail->product);

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_image' => $product->image,
                'size' => optional($productDetail->size)->name,
                'color' => optional($productDetail->color)->name,
                'product_price' => $detail->price,
                'quantity' => $detail->quantity,
                'total_price' => $detail->total_price,
            ];
        });

        $baseData = [
            'id' => $this->id,
            'products' => $products,
            'order_total' => $this->order->total_price,
            'order_status' => $this->order->status,
            'reason' => $this->reason,
            'status' => $this->status,
        ];

        if ($user->role === 'user') {
            return [
                ...$baseData,
                'bank_account' => $this->bank_account,
                'requested_at' => optional($this->requested_at)->format('d/m/Y H:i'),
                'approved_at' => optional($this->admin_approved_at)->format('d/m/Y H:i'),
                'staff_notes' => $this->staff_notes,
            ];
        }

        if ($user->role === 'staff') {
            return [
                ...$baseData,
                'customer_name' => $this->user->name,
                'requested_at' => optional($this->requested_at)->format('d/m/Y H:i'),
            ];
        }

        if ($user->role === 'admin') {
            return [
                ...$baseData,
                'customer_name' => $this->user->name,
                'description' => $this->description,
                'bank_account' => $this->bank_account,
                'reviewed_by' => optional($this->reviewer)->name,
                'staff_notes' => $this->staff_notes,
                'reviewed_at' => optional($this->reviewed_at)->format('d/m/Y H:i'),
            ];
        }

        return [
            'id' => $this->id,
            'status' => $this->status,
            'message' => 'Thông tin hạn chế với vai trò hiện tại',
        ];
    }
}
