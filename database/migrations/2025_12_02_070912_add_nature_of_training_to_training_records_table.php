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
        Schema::table('training_records', function (Blueprint $table) {
            $table->string('nature_of_training')->nullable();
            $table->string('nature_of_training_other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_records', function (Blueprint $table) {
            $table->dropColumn('nature_of_training');
            $table->dropColumn('nature_of_training_other');
        });
    }
};
