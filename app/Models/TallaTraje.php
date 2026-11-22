<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TallaTraje extends Model
{
    protected $table = 'tallas_traje';
    protected $primaryKey = 'idTalla';
    public $timestamps = false;

    protected $fillable = [
        'idTraje',
        'talla',
        'stock',
    ];

    public function traje()
    {
        return $this->belongsTo(Traje::class, 'idTraje', 'idTraje');
    }
}
