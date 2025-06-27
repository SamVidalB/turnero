<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos'; // Especificar nombre de la tabla

    protected $fillable = [
        'user_id',
        'acciones',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'acciones' => 'array', // Laravel convertirá automáticamente el JSON a array y viceversa
    ];

    /**
     * Obtener el usuario al que pertenece este conjunto de permisos.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
