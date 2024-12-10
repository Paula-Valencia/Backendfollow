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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('nit');
            $table->string('name');
            $table->string('email');
            $table->string('social_reason');
            $table->integer('telephone');
            $table->string('address');
            $table->timestamps();
        });
        DB::table('companies')->insert([
            'nit'=> '78312',
            'name'=> 'CEO',
            'email'=> 'ceo@gmail.com',
            'address'=> 'POpayan',
            'social_reason'=> 'hvbjhb',
            'telephone'=> 12312,
        ]);
        DB::table('companies')->insert([
            'nit'=> '8999',
            'name'=> 'SENA',
            'email'=> 'sena@gmail.com',
            'address'=> 'Popayan',
            'social_reason'=> 'Servicio Nacional de Aprendizaje',
            'telephone'=> 12312,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
