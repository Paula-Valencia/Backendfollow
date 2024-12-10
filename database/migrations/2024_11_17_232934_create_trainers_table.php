<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->integer('number_of_monitoring_hours');
            $table->date('month');
            $table->integer('number_of_trainees_assigned');
            $table->string('network_knowledge');
            $table->date('start_date');
            $table->date('end_date');
            // $table->foreignId('id_user_register')->references('id')->on('user_registers');
            $table->foreignId('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
