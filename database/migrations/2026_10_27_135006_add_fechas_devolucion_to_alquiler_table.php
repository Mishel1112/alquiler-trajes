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
            $table->dateTime('fecha_devolucion_real')->nullable()->after('fecha_devolucion');
            $table->dateTime('fecha_ingreso_stock')->nullable()->after('fecha_devolucion_real');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alquiler', function (Blueprint $table) {
            $table->dropColumn(['fecha_devolucion_real', 'fecha_ingreso_stock']);
        });
    }
};
