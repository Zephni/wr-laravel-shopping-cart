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
        $cookieName = 'wrscl_browser_id';

        if ($uniqueIdFallback === true) {
            $browserId = session($cookieName);

            if (!$browserId && request()->hasCookie($cookieName)) {
                $browserId = request()->cookie($cookieName);
                session()->put($cookieName, $browserId);
            }

            if (!$browserId) {
                $browserId = Str::uuid()->toString();
                session()->put($cookieName, $browserId);
                Cookie::queue(cookie($cookieName, $browserId, $defaultCookieDuration));
            }

            $uniqueIdFallback = $browserId;
        }

        // Step 1: Try to find cart by priority ID
        $cart = !empty($uniqueIdPriority)
            ? static::where('unique_id_priority', $uniqueIdPriority)->first()
            : null;

        // Step 2: If no cart found, try fallback ID
        if (!$cart && $uniqueIdFallback !== false) {
            $cart = static::where('unique_id_fallback', $uniqueIdFallback)->first();

            // ✅ If cart found via fallback and user is now logged in, assign ownership
            if ($cart && !empty($uniqueIdPriority)) {
                $cart->update([
                    'unique_id_priority' => $uniqueIdPriority,
                ]);
            }
        }

        // Step 3: If still no cart, create one
        if (!$cart) {
            $cart = static::create([
                'unique_id_priority' => $uniqueIdPriority,
                'unique_id_fallback' => $uniqueIdFallback,
            ]);
        }

        return $cart;
    }
}
