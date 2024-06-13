<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->tinyInteger('custom_url');
                $table->string('url');
                $table->string('icon')->nullable();
                $table->string('description')->nullable();
                $table->string('category')->nullable();
                $table->uuid('parent_id')->nullable();
                $table->integer('sequence')->nullable();
                $table->tinyInteger('data_authority')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
