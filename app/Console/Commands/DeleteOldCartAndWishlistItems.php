<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeleteOldCartAndWishlistItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old-cart-wishlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete cart and wishlist items older than 24 hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $threshold = Carbon::now()->subHours(24);

         // Get old cart items
        $oldCartItems = DB::table('cart')
        ->where('created_at', '<', $threshold)
        ->get();

        foreach ($oldCartItems as $cartItem) {
            // Increase the quantity of the corresponding offer
            DB::table('offers')
                ->where('id', $cartItem->id)
                ->increment('qty', $cartItem->quantity);

            // Delete the cart item
            DB::table('cart')->where('id', $cartItem->id)->delete();
        }


         // Get old wishlist items
         $oldCartItems = DB::table('wishlist')
         ->where('created_at', '<', $threshold)
         ->get();
 
         foreach ($oldCartItems as $cartItem) {
             // Increase the quantity of the corresponding offer
             DB::table('offers')
                 ->where('id', $cartItem->id)
                 ->increment('qty', $cartItem->quantity);
 
             // Delete the wishlist item
             DB::table('wishlist')->where('id', $cartItem->id)->delete();
         }

      
        $this->info('Old cart and wishlist items deleted successfully.');
        return 0;
    }
}