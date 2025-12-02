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
        if (!Schema::hasTable('training_proofs')) {
            Schema::create('training_proofs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('training_id');
                $table->unsignedBigInteger('user_id');
                $table->string('file_path');
                $table->string('status')->default('pending');
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
                
                // Foreign key constraints
                $table->foreign('training_id')->references('id')->on('training_records')->onDelete('cascade');
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
                $table->foreign('reviewed_by')->references('user_id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_proofs');
    }
};
