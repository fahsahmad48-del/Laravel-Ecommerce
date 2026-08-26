<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Cart;


class CleanOldCarts extends Command
{
    protected $signature = 'cart:clean';
    protected $description = 'Delete old guest cart items';

    public function handle()
    {
        Cart::whereNull('user_id')
            ->where('created_at', '<', Carbon::now()->subDays(30))
            ->delete();

        $this->info('Old guest carts deleted successfully.');
    }
}
