<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS vector;');
        }

        Schema::create('business_knowledges', function (Blueprint $table) use ($driver) {
            $table->id();
            $table->foreignId('business_unit_id')->constrained('business_units')->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();

            if ($driver !== 'pgsql') {
                $table->text('embedding')->nullable();
            }
        });

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE business_knowledges ADD COLUMN embedding extensions.vector(768);');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_knowledges');
    }
};
