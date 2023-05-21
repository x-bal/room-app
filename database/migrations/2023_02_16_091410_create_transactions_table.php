<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('no_trx');
            $table->string('nama_customer');
            $table->string('ticket_code');
            $table->enum('tipe', ['group', 'individual']);
            $table->integer('amount');
            $table->integer('amount_scanned')->default(0);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->integer('harga_ticket');
            $table->integer('kembalian');
            $table->integer('cash');
            $table->integer('discount');
            $table->string('metode');
            $table->integer('gate')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
