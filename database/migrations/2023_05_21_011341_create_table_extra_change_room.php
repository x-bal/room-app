<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableExtraChangeRoom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_change_room', function (Blueprint $table) {
            $table->foreignId('extra_change_id')->constrained('extra_changes');
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('transaction_id')->constrained('transactions');
            $table->integer('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_extra_change_room');
    }
}
