<?php

namespace WebRegulate\LaravelShoppingCart\Classes\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
    public static function getCart(?string $uniqueIdPriority, bool|string $uniqueIdFallback = true, int $defaultCookieDuration = 60 * 24 * 30)
    {
        // Set cookie name
        $cookieName = 'wrscl_browser_id';

        // If uniqueIdFallback is true, use session/cookie ID logic, we need to do this to ensure we have a consistent ID
        // ... across requests including livewire and login / logout requests.
        if ($uniqueIdFallback === true) {
            // Step 1: Check session first — it's always available immediately
            $browserId = session($cookieName);

            // Step 2: If not in session, check cookie (may be missing on first request)
            if (!$browserId && request()->hasCookie($cookieName)) {
                $browserId = request()->cookie($cookieName);
                session()->put($cookieName, $browserId); // cache it for this request
            }

            // Step 3: If still missing, generate and persist
            if (!$browserId) {
                $browserId = Str::uuid()->toString();
                session()->put($cookieName, $browserId);
                Cookie::queue(cookie($cookieName, $browserId, $defaultCookieDuration));
            }

            $uniqueIdFallback = $browserId;
        }

        // If uniqueIdPriority is provided, search by it
        if (!empty($uniqueIdPriority)) {
            $cart = static::where('unique_id_priority', $uniqueIdPriority)->first();
        }
        // If uniqueIdPriority is not provided and uniqueIdFallback is not false, if uniqueIdFallback is true use session ID, else use provided uniqueIdFallback
        elseif ($uniqueIdFallback !== false) {
            $cart = static::where('unique_id_fallback', $uniqueIdFallback)->first();
        }
        // If neither is provided, no cart found
        else {
            $cart = null;
        }

        // If cart does not exist, create it
        if (!$cart) {
            $cart = static::create([
                'unique_id_priority' => $uniqueIdPriority,
                'unique_id_fallback' => $uniqueIdFallback,
            ]);
        }
        // If cart exists but unique ID priority is provided and different, update it
        elseif (!empty($uniqueIdPriority) && $cart->unique_id_priority !== $uniqueIdPriority) {
            $cart->update([
                'unique_id_priority' => $uniqueIdPriority,
            ]);
        }

        return $cart;
    }
}
