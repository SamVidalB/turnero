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
     * Comentada o eliminada ya que la relación principal ahora se maneja desde User -> Permiso.
     * Si se necesita una forma inversa de encontrar usuarios por acción a través de la tabla permisos,
     * se requeriría una lógica más compleja (ej. un scope o un método que consulte el campo JSON).
     */
    // public function users()
    // {
    //     // Esto ya no aplica directamente con el campo JSON en la tabla permisos.
    //     // return $this->belongsToMany(User::class, 'accion_user');
    // }
}
