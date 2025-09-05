<?php

namespace WebRegulate\LaravelShoppingCart\Classes\Models;

use Illuminate\Database\Eloquent\Model;

class WrShoppingCartBase extends Model
{
    /**
     * Retrieves or creates a shopping cart based on a unique identifier and an optional priority identifier.
     * 
     * @param string $uniqueId The unique identifier for the cart (e.g., session ID).
     * @param string|null $uniqueIdPriority An optional priority identifier (e.g., user ID). If provided, it updates the existing cart, and then takes precedence over unique ID for further retrievals.
     * @return static The created or retrieved shopping cart instance.
     */
    public static function getCart(string $uniqueId, ?string $uniqueIdPriority = null)
    {
        // If unique ID priority is empty, use unique ID, otherwise use unique ID priority
        $cart = static::when(
            empty($uniqueIdPriority),
            fn($query) => $query->where('unique_id', $uniqueId),
            fn($query) => $query->where('unique_id_priority', $uniqueIdPriority),
        )->first();

        // If cart does not exist, create it
        if (!$cart) {
            $cart = static::create([
                'unique_id' => $uniqueId,
                'unique_id_priority' => $uniqueIdPriority,
            ]);
        }
        // If cart exists but unique ID priority is provided and different, update it
        // ... this is so we can link session carts to user carts on login / registration
        elseif ($uniqueIdPriority && $cart->secondary_identifier !== $uniqueIdPriority) {
            $cart->unique_id_priority = $uniqueIdPriority;
            $cart->save();
        }

        return $cart;
    }
}
