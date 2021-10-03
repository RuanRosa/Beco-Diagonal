<?php

namespace database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsers extends Migration
{
    private $schema;

    public function __construct(
        Schema $schema
    ) {
        $this->schema = $schema;
    }

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function downTable()
    {
        $this->schema
            ->dropIfExists('users');
    }
}
