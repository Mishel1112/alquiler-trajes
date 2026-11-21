<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traje extends Model
{
    use HasFactory;

    protected $table = 'traje';
    protected $primaryKey = 'idTraje';
    public $timestamps = true;

    protected $fillable = [
        'idCategoria',
        'id_Categoria',
        'nombre',
        'pieza',
        'cant_piezas',
        'precio',
        'estado',
        'descripcion',
        'imagen',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'idCategoria');
    }

    public function tallas()
    {
        return $this->hasMany(TallaTraje::class, 'idTraje', 'idTraje');
    }

    public function detallesAlquiler()
    {
        return $this->hasMany(DetalleAlquiler::class, 'idTraje', 'idTraje');
    }

    public function getStockTotalAttribute()
    {
        return $this->tallas()->sum('stock');
    }
}
