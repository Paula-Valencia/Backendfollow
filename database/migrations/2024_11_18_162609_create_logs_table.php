<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->integer('number_log');
            $table->string('description');
            $table->date('date');
            $table->string('observation');
            $table->enum('state', ['approved', 'refused', 'pending'])->default('pending');
            $table->foreignId('id_trainer')->references('id')->on('trainers')->onDelete('cascade');
            $table->foreignId('id_apprentice')->references('id')->on('apprentices')->onDelete('cascade');
            $table->timestamps();
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
