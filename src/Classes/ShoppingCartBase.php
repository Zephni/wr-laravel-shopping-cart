<?php
namespace WebRegulate\LaravelShoppingCart\Classes;

use Illuminate\Database\Eloquent\Model;
use WebRegulate\LaravelShoppingCart\Classes\Traits\CartItem;

abstract class ShoppingCartBase
{
    /**
     * Unique identifier for the shopping cart
     *
     * @var string
     */
    protected string $uniqueId;

    /**
     * Shopping cart data. Note that each item in the cart MUST use this format (use the buildCartItemData method from the CartItem trait):
     * [
     *     'model' => null, // Make sure extending classes call parent::__construct($uniqueId), this will set the unique identifier and load the shopping cart data, then loop through the data and set the model instances
     *     'modelClass' => 'App\Models\Product', // The product model associated with this item. Note that the model must implement the CartItem trait and set it's getCartName, getCartOptions, and getCartPrice methods
     *     'modelId' => 1, // The ID of the product model
     *     'quantity' => 1, // The quantity of this item
     *     'options' => [] // The options of this item
     * ]...
     *
     * @var array
     */
    protected array $shoppingCartData = [];

    /**
     * Constructor
     *
     * @param string $uniqueId
     * @return static
     */
    public function __construct(string $uniqueId)
    {
        // Set unique identifier and load shopping cart data
        $this->uniqueId = $uniqueId;
        $this->load();

        // Loop through shopping cart data and set model instances
        foreach ($this->shoppingCartData as $key => $item) {
            $this->shoppingCartData[$key]['model'] = app($item['modelClass'])->find($item['modelId']);
        }
    }

    /**
     * Saves current data to storage with $this->uniqueId
     *
     * @return bool True if successful, false otherwise
     */
    abstract public function save(): bool;

    /**
     * Retrieves the data from storage and promises to store in data property
     *
     * @return void
     */
    abstract public function load(): void;

    /**
     * Add cart item, adds new, or increments quantity and merges options if item already exists
     *
     * @param Model $model
     * @param float $quantity
     * @param array $options
     */
    public function addCartItem(Model $model, float $quantity = 1, array $options = []): void
    {
        // Check valid cart item model
        $this->throwExceptionIfInvalidCartItemModel($model);

        // If item already exists in cart, merge options and update quantity
        foreach ($this->shoppingCartData as $key => $item) {
            if ($item['model'] == get_class($model) && $item['modelId'] == $model->id) {
                $this->shoppingCartData[$key]['quantity'] += $quantity;
                $this->shoppingCartData[$key]['options'] = array_merge($this->shoppingCartData[$key]['options'], $options);
                $this->save();
                return;
            }
        }

        // Otherwise append and save
        $this->shoppingCartData[] = $model->buildCartItemData($quantity, $options);
        $this->save();
    }

    /**
     * Set cart item, forcibly sets quantity and options of existing cart item
     *
     * @param Model $model
     * @param float $quantity
     * @param array $options
     */
    public function setCartItem(Model $model, float $quantity = 1, array $options = []): void
    {
        // Check valid cart item model
        $this->throwExceptionIfInvalidCartItemModel($model);

        // If item already exists in cart, update quantity
        foreach ($this->shoppingCartData as $key => $item) {
            if ($item['model'] == get_class($model) && $item['modelId'] == $model->id) {
                $this->shoppingCartData[$key]['quantity'] = $quantity;
                $this->shoppingCartData[$key]['options'] = $options;
                return;
            }
        }
    }

    /**
     * Remove cart item by row index
     *
     * @param int $rowIndex
     * @return static
     */
    public function removeCartItem(int $rowIndex): void
    {
        unset($this->shoppingCartData[$rowIndex]);
        $this->save();
    }

    /**
     * Remove all cart items
     *
     * @return static
     */
    public function removeAllCartItems(): void
    {
        $this->shoppingCartData = [];
        $this->save();
    }

    /**
     * Remove cart item
     *
     * @param Model $model
     * @param array $options
     */
    public function removeCartItemByModelAndOptions(Model $model, array $options = []): void
    {
        // Check valid cart item model
        $this->throwExceptionIfInvalidCartItemModel($model);

        // If item already exists in cart, remove it
        foreach ($this->shoppingCartData as $key => $item) {
            if ($item['model'] == get_class($model) && $item['modelId'] == $model->id && $item['options'] == $options) {
                unset($this->shoppingCartData[$key]);
                return;
            }
        }
    }

    /**
     * Get cart item models
     *
     * @return array
     */
    public function getCartItems(): array
    {
        return $this->shoppingCartData;
    }

    /**
     * Get cart item count
     *
     * @return int
     */
    public function getCartItemsCount(): int
    {
        return count($this->shoppingCartData);
    }

    /**
     * Get total price of all cart items
     *
     * @return float
     */
    public function getCartTotalPrice(): float
    {
        $totalPrice = 0;
        foreach ($this->shoppingCartData as $item) {
            $totalPrice += $item['model']->getCartPrice($item['quantity'], $item['options']);
        }

        return $totalPrice;
    }

    /**
     * Get total price in lowest currency unit (e.g. pence) of all cart items
     *
     * @return int
     */
    public function getCartTotalPriceInLowestCurrencyUnit(): int
    {
        return round($this->getCartTotalPrice() * 100);
    }

    /**
     * Get shopping cart data array without model instances
     *
     * @return array
     */
    public function getShoppingCartDataWithoutModels(): array
    {
        // Unset model instances
        $shoppingCartData = $this->shoppingCartData;
        foreach ($shoppingCartData as $key => $item) {
            unset($shoppingCartData[$key]['model']);
        }

        return $shoppingCartData;
    }

    /**
     * Throw exception if invalid cart item model
     *
     * @param Model $model
     * @return void
     */
    private function throwExceptionIfInvalidCartItemModel(Model $model): void
    {
        if (!in_array(CartItem::class, class_uses($model))) {
            throw new \Exception('Model '.$model::class.' must use the WebRegulate\LaravelShoppingCart\Classes\Traits\CartItem trait');
        }
    }
}
