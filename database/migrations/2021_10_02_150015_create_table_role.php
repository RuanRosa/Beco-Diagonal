<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\RoleSeeder;
/**
 * Suppress all warnings from these two rules.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
class CreateTableRole extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->timestamps();
            });

        $seed = new RoleSeeder();
        $seed->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
