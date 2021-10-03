<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\database\Seeders\RoleSeeder;

class CreateTableRole extends Migration
{
    public function upTable()
    {
        $this->schema
            ->create('roles', function (Blueprint $table) {
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
    public function downTable()
    {
        $this->schema
            ->dropIfExists('roles');
    }
}
