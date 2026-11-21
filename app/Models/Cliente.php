<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'idCliente';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'telefono',
        'tipo_cliente',
        'fraternidad',
        'colegio',
        'curso',
        'lugar_baile'
    ];

    public function alquileres()
    {
        return $this->hasMany(Alquiler::class, 'idCliente', 'idCliente');
    }
}
