<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class AddStockOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $priority = DB::table('stock')
            ->max('priority');

        DB::table('stock')->insert([
            'slug'     => 'allnation',
            'title'    => 'CD Allnation',
            'priority' => ((int) $priority + 1)
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('stock')
            ->where('slug', '=', 'allnation')
            ->delete();
    }
}
