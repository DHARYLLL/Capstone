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
        Schema::table('chat_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_sessions', 'business_unit_id')) {
                $table->foreignId('business_unit_id')
                      ->nullable()
                      ->after('company_id')
                      ->constrained('business_units')
                      ->onDelete('cascade');
            }

            if (!Schema::hasColumn('chat_sessions', 'user_identifier')) {
                $table->string('user_identifier')->nullable()->after('business_unit_id')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropForeign(['business_unit_id']);
            $table->dropColumn(['business_unit_id', 'user_identifier']);
        });
    }
};
