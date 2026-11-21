<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleAlquiler extends Model
{
    protected $table = 'detalle_alquiler';
    protected $primaryKey = 'idDetalleAlquiler';
    public $timestamps = false;

    protected $fillable = [
        'idAlquiler',
        'idTraje',
        'cantidad',
        'cantidadPiezas',
        'precio',
        'subTotal',
    ];

    public function alquiler()
    {
        return $this->belongsTo(Alquiler::class, 'idAlquiler', 'idAlquiler');
    }

    public function traje()
    {
        return $this->belongsTo(Traje::class, 'idTraje', 'idTraje');
    }
}
