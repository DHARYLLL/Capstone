<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table): void {
            if (! Schema::hasColumn('chat_sessions', 'business_unit_id')) {
                $table->foreignId('business_unit_id')->nullable()->after('company_id')->constrained('business_units')->nullOnDelete();
            }

            if (! Schema::hasColumn('chat_sessions', 'status')) {
                $table->string('status', 30)->default('bot_active')->after('company_id');
            }

            if (! Schema::hasColumn('chat_sessions', 'assigned_user_id')) {
                $table->foreignId('assigned_user_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('chat_sessions', 'user_identifier')) {
                $table->string('user_identifier')->nullable()->after('assigned_user_id');
                $table->index('user_identifier');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('assigned_user_id');
            $table->dropConstrainedForeignId('business_unit_id');
            $table->dropIndex(['user_identifier']);
            $table->dropColumn(['user_identifier', 'status', 'assigned_user_id', 'business_unit_id']);
        });
    }
};
