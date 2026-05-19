<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('product_fee', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('fee_id');

            // Variation type: either 'number' or 'percentage'
            $table->enum('variation_type', ['number', 'percentage']);

            // Variation ammount: a decimal that can be negative
            $table->decimal('variation_ammount', 8, 2);

            // Quantity of each product to buy
            $table->integer('quantity')->default(1);

            // Composite primary key (you can also add an auto-increment column if desired)
            $table->primary(['product_id', 'fee_id']);

            // Foreign keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('fee_id')->references('id')->on('fees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_fee');
    }
};
