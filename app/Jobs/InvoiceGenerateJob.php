<?php

namespace App\Jobs;

use App\Services\Checkout\InvoiceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceGenerateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $type = 1)
    {
        $this->order = $order;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Invoice generating process and sending email to the customer email.
        $invoice = new InvoiceService();
        $invoice->generateInvoiceByOrderId($this->order, $this->type);
    }
}
