<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderDeliveredMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load([
            'order_details.productDetail.product',
            'order_details.productDetail.color',
            'order_details.productDetail.size'
        ]);
    }
    
    public function build()
{
    $url = url('/api/orders/' . $this->order->id . '/confirm-receipt') . '?token=' . $this->generateToken();

    return $this->subject('Đơn hàng đã được giao')
                ->markdown('emails.orders.delivered')
                ->with([
                    'order' => $this->order,
                    'confirmUrl' => $url,
                ]);
}

protected function generateToken()
{
    return sha1($this->order->id . $this->order->email . config('app.key'));
}
}
