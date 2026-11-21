<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    protected $table = 'alquiler';
    protected $primaryKey = 'idAlquiler';
    
    // La tabla alquiler solo tiene created_at, sin updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'idCliente',
        'idGarantia',
        'idUsuario',
        'f_recogida',
        'f_devolucion',
        'estado',
        'tipo_operacion',
    ];

    protected $casts = [
        'f_recogida' => 'datetime',
        'f_devolucion' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }

    public function garantia()
    {
        return $this->belongsTo(Garantia::class, 'idGarantia', 'idGarantia');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleAlquiler::class, 'idAlquiler', 'idAlquiler');
    }

    public function devolucion()
    {
        return $this->hasOne(Devolucion::class, 'idAlquiler', 'idAlquiler');
    }
}
