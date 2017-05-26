<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->string('corretora')->nullable();
            $table->string('conta')->nullable();
            $table->string('titular')->nullable();
            $table->string('sub_conta')->nullable();
            $table->string('sub_titular')->nullable();
            $table->string('clordid')->unique();
            $table->string('ativo')->nullable();
            $table->string('lado')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('criacao')->nullable();
            $table->timestamp('ultima_atualizacao')->nullable();
            $table->string('preco')->nullable();
            $table->string('preco_stop')->nullable();
            $table->string('quantidade')->nullable();
            $table->string('preco_medio')->nullable();
            $table->string('quantidade_executada')->nullable();
            $table->string('quantidade_restante')->nullable();
            $table->string('total')->nullable();
            $table->string('total_executado')->nullable();
            $table->string('validade')->nullable();
            $table->string('data_validade')->nullable();
            $table->string('estrategia')->nullable();
            $table->string('mensagem')->nullable();
            $table->string('emolumento');
            $table->string('corretagem');
            $table->string('ganho');
            $table->integer('historical_result_id')->unsigned();
            $table->foreign('historical_result_id')
                ->references('id')->on('historical_results')
                ->onDelete('cascade');
            $table->integer('rate_types_id')->unsigned();
            $table->foreign('rate_types_id')
                ->references('id')->on('rate_types')
                ->onDelete('cascade');
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
        Schema::dropIfExists('results');
    }
}
