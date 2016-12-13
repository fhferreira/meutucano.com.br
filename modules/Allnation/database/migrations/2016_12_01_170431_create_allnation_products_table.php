<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateAllnationProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allnation_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produto_sku')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('brand', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('ean', 20)->nullable();
            $table->string('ncm', 20)->nullable();
            $table->string('warranty', 20)->nullable();
            $table->decimal('weigth', 10, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('stock_from', 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->string('origin', 100)->nullable();
            $table->nullableTimestamps();

            $table->foreign('produto_sku', 'ProductSku')
                ->references('sku')
                ->on('produtos')
                ->onDelete('SET NULL')
                ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allnation_products');
    }
}
