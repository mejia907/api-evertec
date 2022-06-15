<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->String('custumer_ducument_type', 8);
            $table->String('custumer_ducument', 40);
            $table->String('custumer_name', 80);
            $table->String('custumer_email', 120);
            $table->String('custumer_mobile', 40);
            $table->String('status', 20);
            $table->integer('request_id')->nullable()->default(0);
            $table->String('process_url', 250)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
