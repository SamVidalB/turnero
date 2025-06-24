<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'ruta'];

    /**
     * The users that belong to the Accion.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'permisos', 'accion_id', 'usuario_id');
    }
}
