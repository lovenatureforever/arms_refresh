<?php

namespace App\Mail;

use App\Models\Tenant\CosecOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CosecOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $type;
    public $formPath;

    public function __construct(CosecOrder $order, $type = 'created', $formPath = null)
    {
        $this->order = $order;
        $this->type = $type;
        $this->formPath = $formPath;
    }

    public function build()
    {
        $subject = match($this->type) {
            'created' => "New Cosec Order: {$this->order->form_name}",
            'approved' => "Order Approved: {$this->order->form_name}",
            'signature_required' => "Signature Required: {$this->order->form_name}",
            'completed' => "Order Completed: {$this->order->form_name}",
            default => "Cosec Order Update"
        };

        $mail = $this->subject($subject)
                    ->view('emails.cosec-order-notification');

        if ($this->formPath) {
            $mail->attach(storage_path("app/public/{$this->formPath}"));
        }

        return $mail;
    }
}