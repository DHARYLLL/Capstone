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
            $table->string('role')->default('agent')->after('password');
            $table->foreignId('company_id')->nullable()->after('role')->constrained()->nullOnDelete();
            $table->foreignId('business_unit_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('business_unit_id');
            $table->dropConstrainedForeignId('company_id');
            $table->dropColumn('role');
        });
    }
};
