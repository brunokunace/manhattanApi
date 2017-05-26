<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cpf')->unique();
            $table->string('rg');
            $table->string('rg_emissor');
            $table->string('rg_uf');
            $table->string('data_nascimento');
            $table->string('sexo');
            $table->string('naturalidade');
            $table->string('estado_civil');
            $table->string('cep');
            $table->string('logradouro');
            $table->string('numero');
            $table->string('bairro');
            $table->string('localidade');
            $table->string('uf');
            $table->string('skype');
            $table->string('telefone_fixo');
            $table->string('telefone_celular');
            $table->string('cod_subconta');
            $table->string('ativo')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
