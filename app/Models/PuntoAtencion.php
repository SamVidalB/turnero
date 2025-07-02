<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoAtencion extends Model
{
    use HasFactory;

    protected $table = 'puntos_atencion';

    protected $fillable = [
        'nombre',
        'sede_id',
    ];

    /**
     * Obtener la sede a la que pertenece este punto de atención.
     */
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
}
