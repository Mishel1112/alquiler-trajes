<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devolucion';
    protected $primaryKey = 'idDevolucion';
    public $timestamps = false;

    protected $fillable = [
        'idAlquiler',
        'f_devolucion',
        'f_real_devolucion',
        'estado',
        'cobroDano',
        'monto_devuelto',
        'observacion',
        'idUsuario',
        'f_ingreso_stock',
    ];

    public function alquiler()
    {
        return $this->belongsTo(Alquiler::class, 'idAlquiler', 'idAlquiler');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}
