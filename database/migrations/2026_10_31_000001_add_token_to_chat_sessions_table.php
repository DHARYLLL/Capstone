<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table): void {
            $table->string('token', 64)->nullable()->unique()->after('id');
        });

        DB::table('chat_sessions')
            ->whereNull('token')
            ->orderBy('id')
            ->chunkById(500, function ($sessions): void {
                foreach ($sessions as $session) {
                    DB::table('chat_sessions')
                        ->where('id', $session->id)
                        ->update(['token' => Str::random(64)]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table): void {
            $table->dropUnique(['token']);
            $table->dropColumn('token');
        });
    }
};
