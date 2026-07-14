<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        // Precargamos las relaciones necesarias
        $this->order = $order->load('user', 'orderItems.product', 'shippingAddress');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧾 Confirmación de tu pedido #' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->subject('🧾 Confirmación de tu pedido #' . $this->order->id)
                    ->markdown('emails.order-confirmation')
                    ->with([
                        'order' => $this->order,
                        'support_email' => 'ecoommerce.andercode@anderson-bastidas.com',
                    ]);
    }
}
