<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_vendas', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor_item')->default(0);
            $table->decimal('desconto')->default(0);
            $table->integer('numero_item');
            $table->integer('quantidade_itens')->default(0);
            $table->foreignId('produto_id')->references('id')->on('produtos');
            $table->foreignUuid('venda_id')->references('id')->on('vendas');
            $table->softDeletes();
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
        Schema::dropIfExists('item_vendas');
    }
}
