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
        // If the table already exists (e.g. legacy DB), skip creation to avoid errors
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('user_id');
                $table->string('full_name');
                $table->string('email')->unique();
                $table->string('password_hash');
                $table->string('role')->default('unassigned');
                $table->string('office_code')->nullable();
                $table->boolean('is_approved')->default(false);
                $table->timestamp('created_at')->useCurrent();
            });
        }
}
};
