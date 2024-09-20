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
        Schema::create('talents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('resume')->nullable(); // To store the file path of the uploaded resume
            $table->integer('years_of_experience')->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->text('previous_work_portfolio')->nullable();
            $table->string('specialization')->nullable();
            $table->text('technical_skills')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talents');
    }
};
