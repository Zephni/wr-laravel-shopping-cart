<?php

namespace WebRegulate\LaravelShoppingCart\Classes\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class WrShoppingCart extends WrShoppingCartBase
{
    use SoftDeletes;

    protected $table = 'wr_shopping_carts';
}
