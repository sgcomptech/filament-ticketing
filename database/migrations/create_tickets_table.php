<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticketable_type')->nullable();
            $table->bigInteger('ticketable_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('assigned_to_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('priority')->default(0);
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }
};
