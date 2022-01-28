<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_brand_id')->unsigned();
            $table->bigInteger('product_unit_id')->unsigned();
            $table->string('name', 60);
            $table->longText('description');
            $table->string('sku', 60);
            $table->enum('type_id', ['simple', 'configurable'])->default('simple');
            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->longText('image_url');
            $table->enum('enable_discount', ['0', '1'])->default('0');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes('deleted_at', 0);
            $table->timestamps();


            $table->foreign('product_brand_id')->references('id')->on('product_brands');
            $table->foreign('product_unit_id')->references('id')->on('product_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
