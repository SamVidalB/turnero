<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ruta',
        'modulo' // Añadido para agrupación en la UI
    ];

    /**
     * Los usuarios que tienen esta acción.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'accion_user'); // 'accion_user' será nuestra tabla pivote
    }
}
