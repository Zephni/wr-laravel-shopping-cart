<?php
namespace WebRegulate\LaravelShoppingCart\Classes\Drivers;

use WebRegulate\LaravelShoppingCart\Classes\Models\WrShoppingCart;

class ShoppingCartDriverDatabase extends ShoppingCartDriverBase
{
    /**
     * Model class
     */
    public string $modelClass;

    /**
     * Unique ID for the cart
     */
    public ?string $uniqueId;

    /**
     * Session prefix
     */
    public string $sessionPrefix;

    /**
     * Forget session when unique ID exists
     */
    public bool $forgetSessionOnUniqueId;

    /**
     * Additional data, stored in additional_data column
     */
    public array $additionalData = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set properties from config
        $this->modelClass = $this->getHandlerConfig()['model'];
        $this->uniqueId = $this->getHandlerConfig()['unique_id']();
        $this->sessionPrefix = $this->getHandlerConfig()['session_prefix'];
        $this->forgetSessionOnUniqueId = $this->getHandlerConfig()['forget_session_on_unique_id'] ?? false;

        // Call parent constructor
        parent::__construct();
    }

    /**
     * Saves current data to storage
     * 
     * @return bool True if successful, false otherwise
     */
    public function save(): bool
    {
        // Always save to session
        session()->put("{$this->sessionPrefix}.cart_data", $this->getShoppingCartDataWithoutModels());

        // If unique ID is null, just use session
        if (is_null($this->uniqueId)) {
            return true;
        }

        // If unique ID is set, get cart from database and update or create
        $model = $this->modelClass;
        $cart = $model::where('unique_id', $this->uniqueId)->first();

        // If cart exists, update it
        if ($cart) {
            $cart->cart_data = $this->getShoppingCartDataWithoutModels();
            $cart->save();
        }
        // Otherwise create new cart record
        else {
            $model::create([
                'unique_id' => $this->uniqueId,
                'cart_data' => $this->getShoppingCartDataWithoutModels(),
            ]);
        }

        return true;
    }

    /**
     * Retrieves the data from storage and promises to store in data property
     */
    public function load(): void
    {
        // If unique ID is null, just use session
        if (is_null($this->uniqueId)) {
            $this->shoppingCartData = session()->get("{$this->sessionPrefix}.cart_data", []);
            return;
        }

        // Create cart if it doesn't exist with the given unique ID
        $cart = $this->getAndHandleCartUpdates($this->uniqueId, false);

        // Load cart data from cart or fall back to empty array
        $this->shoppingCartData = json_decode($cart->cart_data ?? '[]', true) ?? [];

        // Load additional data if exists
        $this->additionalData = json_decode($cart->additional_data ?? '[]', true) ?? [];
    }

    /**
     * Handles database operations for the shopping cart, including storing,
     * retrieving, updating, and deleting cart items and related data.
     *
     * @param mixed $param1 Description of the first parameter.
     * @param mixed $param2 Description of the second parameter.
     * @return mixed Shopping cart instance or null
     */
    public function getAndHandleCartUpdates(string $uniqueId, bool $allowUpdate = true, bool $deleteIfEmpty = true): mixed
    {
        // Get cart data from session
        $cartData = session()->get("{$this->sessionPrefix}.cart_data", []);


        // If unique ID is set, load from database
        $model = $this->modelClass;
        $cart = $model::where('unique_id', $uniqueId)->first();

        // If cart exists and deleteIfEmpty is true and cart is empty, force delete and return null
        if($cart && $deleteIfEmpty) {
            $cartData = json_decode($cart->cart_data ?? '[]', true) ?? [];
            if(empty($cartData) || (is_array($cartData) && count($cartData) === 0)) {
                $cart->forceDelete();
                $cart = null;
            }

            return $cart;
        }

        // If cart doesn't exist, create it from session data
        if (!$cart) {
            // Create new cart record with session data
            $cart = $model::create([
                'unique_id' => $uniqueId,
                'cart_data' => json_encode($cartData),
            ]);
        }
        // Otherwise update existing cart with session data
        else if($allowUpdate) {
            $cart->cart_data = json_encode($cartData);
            $cart->save();
        }

        return $cart;
    }

    /**
     * Get shopping cart model instance
     * 
     * @param ?string Leave null to get from current session, or pass a unique ID to get specific cart
     */
    public function getCartModel(?string $uniqueId = null): mixed
    {
        if(is_null($uniqueId)) {
            return WrShoppingCart::where('unique_id', $this->uniqueId)->first();
        }

        return WrShoppingCart::where('unique_id', $uniqueId)->first();
    }

    /**
     * Forget session data
     */
    public function forget(): void
    {
        session()->forget("{$this->sessionPrefix}.cart_data");
    }
}