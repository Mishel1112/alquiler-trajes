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
        'id_Rol', 
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
        $rolId = (int) ($this->idRol ?? $this->id_Rol ?? 0);
        return $rolId === 1 || (strtolower(optional($this->rol)->nombre ?? '') === 'administrador');
    }

    public function esEmpleado(): bool
    {
        $rolId = (int) ($this->idRol ?? $this->id_Rol ?? 0);
        return $rolId === 2 || (strtolower(optional($this->rol)->nombre ?? '') === 'empleado');
    }
}
