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
        Schema::table('users', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->after('password_hash');
            }
            if (!Schema::hasColumn('users', 'office_code')) {
                $table->string('office_code')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('office_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'full_name')) {
                $columnsToDrop[] = 'full_name';
            }
            if (Schema::hasColumn('users', 'office_code')) {
                $columnsToDrop[] = 'office_code';
            }
            if (Schema::hasColumn('users', 'is_approved')) {
                $columnsToDrop[] = 'is_approved';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
