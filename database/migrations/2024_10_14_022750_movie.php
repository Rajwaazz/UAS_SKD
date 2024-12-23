<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('poster')->nullable();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('genre');
            $table->date('tanggalRilis');
            $table->integer('duration');
            $table->enum('status', ['now playing', 'upcoming', 'expired']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
