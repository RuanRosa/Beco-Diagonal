<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableUsers extends Migration
{
    public function upTable()
    {
        $this->schema
            ->create('users', function (Blueprint $table) {
                $table->id();

                $table->string('name');

                $table->integer('cpf')
                    ->unique();

                $table->string('email')
                    ->unique();

                $table->string('password');

                $table->timestamps();
            });
    }

    public function downTable()
    {
        $this->schema
            ->dropIfExists('users');
    }
}
