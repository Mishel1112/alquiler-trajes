<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';
    protected $primaryKey = 'idUsuario';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
        'password',
        'idRol', 
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'idRol'); 
    }

    public function esAdmin(): bool
    {
        return (int) $this->idRol === 1 || (strtolower(optional($this->rol)->nombre ?? '') === 'administrador');
    }

    public function esEmpleado(): bool
    {
        return (int) $this->idRol === 2 || (strtolower(optional($this->rol)->nombre ?? '') === 'empleado');
    }
}
