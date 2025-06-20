<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'documento',
        'email',
        'password',
        'rol',
        'estado'
    ];

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class);
    }
    
}
