<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuVisibilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menu_visibilities')) {
            Schema::create('menu_visibilities', function (Blueprint $table) {
                $table->uuid('role_id');
                $table->uuid('menu_id');

                $table->foreign('menu_id')
                    ->references('id')
                    ->on('menus')
                    ->onDelete('cascade');

                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');

                $table->primary(['role_id', 'menu_id']);
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
        Schema::dropIfExists('menu_visibilities');
    }
}
