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
        Schema::create('training_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('assigned_by');
            $table->date('assigned_date');
            $table->date('deadline');
            $table->string('status')->default('pending'); // pending, completed, overdue
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('training_id')->references('id')->on('training_records')->onDelete('cascade');
            $table->foreign('staff_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_assignments');
    }
};
