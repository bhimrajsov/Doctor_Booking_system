<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->integer('doctor_id');
            $table->date('appointment_date');
            $table->time('appointment_start_time');
            $table->time('appointment_end_time');
            $table->enum('status', ['RSVP', 'approved', 'cancelled', 'postponed'])->default('RSVP');
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('patient_id')->references('id')->on('users');
            $table->foreign('doctor_id')->references('id')->on('doctors');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
