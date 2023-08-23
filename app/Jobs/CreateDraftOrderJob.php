<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateDraftOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->order['note_attributes']) {
            foreach ($this->order['note_attributes'] as $attribute) {
                if ($attribute['name'] === 'commission_id') {
                    $commission_id = $attribute['value'];
                    DB::table('draft_orders')
                        ->where('commission_id', $commission_id)
                        ->update(['commission_status' => 'ready']);
                }
            }
            Log::alert('order status has been updated.');
        }
    }
}
