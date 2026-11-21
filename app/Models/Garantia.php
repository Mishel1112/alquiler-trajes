<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garantia extends Model
{
    protected $table = 'garantia';
    protected $primaryKey = 'idGarantia';
    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'monto',
        'descripcion'
    ];

    public function alquileres()
    {
        return $this->hasMany(Alquiler::class, 'idGarantia', 'idGarantia');
    }
}
