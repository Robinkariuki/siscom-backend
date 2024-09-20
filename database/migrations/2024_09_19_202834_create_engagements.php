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
        Schema::create('engagements', function (Blueprint $table) { // Corrected the table name here
            $table->id();
            $table->string('name');       // Add name field
            $table->string('email');      // Add email field
            $table->string('phone');      // Add phone field
            $table->string('company');    // Add company field
            $table->text('message');      // Add message field
            $table->date('date');         // Add date field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagements'); // Corrected the table name here
    }
};
