<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_instances', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method')->nullable();
            $table->string('return_url')->nullable();
            $table->string('intent_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('status')->nullable();
            $table->string('amount')->nullable();
            $table->string('currency')->nullable();
            $table->text('request_options')->nullable();
            $table->text('response_object')->nullable();
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
        Schema::dropIfExists('payment_instances');
    }
};
