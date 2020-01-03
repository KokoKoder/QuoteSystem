<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
			$table->char('name',532);
			$table->char('URL',532);
			$table->text('description')->nullable();;
			$table->integer('pictures')->nullable();;
			$table->text('metaDesc')->nullable();;
			$table->char('metaTile',532)->nullable();;
			$table->integer('lang');
			$table->integer('vendor');
			$table->integer('parentCategory')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
