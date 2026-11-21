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
        Schema::table('alquiler', function (Blueprint $table) {
            $table->boolean('es_reserva')->default(false)->after('estado');
            $table->string('estado_reserva', 50)->nullable()->after('es_reserva');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alquiler', function (Blueprint $table) {
            $table->dropColumn(['es_reserva', 'estado_reserva']);
        });
    }
};
