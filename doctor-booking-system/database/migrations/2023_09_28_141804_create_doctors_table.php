<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->integer('phone');
            $table->string('image')->nullable();
            $table->string('speciality');
            $table->string('languages')->nullable();
            $table->string('education')->nullable();
            $table->string('password');
            $table->date('DOB');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('device_token')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};
