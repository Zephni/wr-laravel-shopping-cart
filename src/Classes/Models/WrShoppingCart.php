<?php

namespace WebRegulate\LaravelShoppingCart\Classes\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class WrShoppingCart extends WrShoppingCartBase
{
    use SoftDeletes;

    protected $table = 'wr_shopping_carts';
    
    protected $fillable = [
        'unique_id',
        'additional_data',
        'cart_data',
    ];

    /**
     * Set additional data key/value
     * 
     * @param string $key Dotted key notation
     */
    public function setAdditionalData(string $key, mixed $value): static
    {
        // Decode from additional data column
        $additonalData = json_decode($this->additonal_data, true) ?? [];

        // Set value using dot notation
        data_set($additonalData, $key, $value);

        // Save changes
        $this->saveAdditionalData($additonalData);

        return $this;
    }

    /**
     * Remove additional data key
     */
    public function removeAdditionalData(string $key): static
    {
        // Decode from additional data column
        $additonalData = json_decode($this->additonal_data, true) ?? [];

        // Remove value using dot notation
        data_set($additonalData, $key, null);

        // Save changes
        $this->saveAdditionalData($additonalData);

        return $this;
    }

    /**
     * Reset additional data
     */
    public function resetAdditionalData(): static
    {
        // Save changes
        $this->saveAdditionalData([]);

        return $this;
    }

    /**
     * Save additional data to storage
     */
    public function saveAdditionalData(array $additionalData): void
    {
        $this->additional_data = json_encode($additionalData);
        $this->save();
    }

    /**
     * Get additional data value by key
     * 
     * @param string $key Dotted key notation, or null for all data
     */
    public function getAdditionalData(?string $key = null, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return json_decode($this->additional_data, true) ?? [];
        }

        $additionalData = json_decode($this->additional_data, true) ?? [];

        return data_get($additionalData, $key, $default);
    }
}
