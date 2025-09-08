<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wr_shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id_priority', 255)->nullable()->index();
            $table->string('unique_id_fallback', 255)->nullable()->index();
            $table->json('cart_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wr_shopping_carts');
    }
};
