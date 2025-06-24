<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos'; // Especificar el nombre de la tabla si no sigue la convención de Laravel

    protected $fillable = ['usuario_id', 'acciones'];

    /**
     * Get the user that owns the permission.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
