<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class GenerateOrderTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:generate-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tokens for orders that do not have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::whereNull('token')->get();

        if ($orders->isEmpty()) {
            $this->info('No orders without tokens found.');
            return;
        }

        $count = 0;
        foreach ($orders as $order) {
            $order->token = bin2hex(random_bytes(32));
            $order->save();
            $count++;
        }

        $this->info("Generated tokens for {$count} orders.");
    }
}
