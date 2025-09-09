<?php
namespace WebRegulate\LaravelShoppingCart\Classes\Drivers;

class ShoppingCartDatabase extends ShoppingCartBase
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

        // If unique ID is set, load from database
        $model = $this->modelClass;
        $cart = $model::where('unique_id', $this->uniqueId)->first();

        // If cart doesn't exist, create it from session data
        if (!$cart) {
            // Create new cart record with session data
            $cart = $model::create([
                'unique_id' => $this->uniqueId,
                'cart_data' => json_encode(session()->get("{$this->sessionPrefix}.cart_data", [])),
            ]);
        }

        $this->shoppingCartData = json_decode($cart->cart_data ?? '[]', true) ?? [];
    }
}