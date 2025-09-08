<?php

namespace WebRegulate\LaravelShoppingCart\Classes\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Model;

class WrShoppingCartBase extends Model
{
    /**
     * Retrieves or creates a shopping cart based on a unique identifier and an optional priority identifier.
     * 
     * @param string|null $uniqueIdPriority An optional priority identifier (e.g., user ID). If provided, it updates the existing cart, and then takes precedence over unique ID for further retrievals.
     * @param bool|string $uniqueId true: Use session ID, false: No fallback, string: Pass a secondary unique identifier to be used as a fallback if $uniqueIdPriority is null (e.g., session ID).
     * @param int $defaultCookieDuration Duration in minutes for which the cookie should last if the default $uniqueId is true is used, this way it can be customized as needed.
     * @return static The created or retrieved shopping cart instance.
     */
    public static function getCart(?string $uniqueIdPriority, bool|string $uniqueId = true, int $defaultCookieDuration = 60 * 24 * 30)
    {
        // If $uniqueId is true, use session ID
        if ($uniqueId === true) {
            // Set cookie if not already set
            if (!request()->hasCookie('wrscl_browser_id')) {
                $browserId = Str::uuid()->toString();
                Cookie::queue('wrscl_browser_id', $browserId, $defaultCookieDuration); // 30 days
            }
        }

        // If uniqueIdPriority is provided, search by it
        if (!empty($uniqueIdPriority)) {
            $cart = static::where('unique_id_priority', $uniqueIdPriority)->first();
        }
        // If uniqueIdPriority is not provided and uniqueId is not false, if uniqueId is true use session ID, else use provided uniqueId
        elseif ($uniqueId !== false) {
            $cart = static::where('unique_id_fallback', $uniqueId)->first();
        }
        // If neither is provided, no cart found
        else {
            $cart = null;
        }

        // If cart does not exist, create it
        if (!$cart) {
            $cart = static::create([
                'unique_id_priority' => $uniqueIdPriority,
                'unique_id_fallback' => $uniqueId,
            ]);
        }
        // If cart exists but unique ID priority is provided and different, update it
        // ... with both the priority and fallback IDs, this means the logged in user will
        // ... always link session carts to user carts on login / registration
        // ... or perhaps across devices for example, but we need to make sure to pass the
        // ... priority id as soon as we have access to it within the get_cart closure
        elseif (!empty($uniqueIdPriority) && $cart->unique_id_priority !== $uniqueIdPriority) {
            $cart->update([
                'unique_id_priority' => $uniqueIdPriority,
            ]);
        }

        return $cart;
    }
}
