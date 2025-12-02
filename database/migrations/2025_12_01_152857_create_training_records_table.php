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
        if (!Schema::hasTable('training_records')) {
            Schema::create('training_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->string('status')->default('pending');
                $table->string('venue');
                $table->boolean('proof_uploaded')->default(false);
                $table->string('office_code');
                $table->string('nature_of_training')->nullable();
                $table->string('nature_of_training_other')->nullable();
                $table->string('scope');
                $table->timestamps();
                
                // Foreign key constraints
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
                $table->foreign('office_code')->references('code')->on('offices')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_records');
    }
};