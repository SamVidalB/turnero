<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'documento',
        'fecha_nacimiento',
        'asegurador_id',
        'categoria_id',
        'estado',
    ];

    public function asegurador()
    {
        return $this->belongsTo(Asegurador::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }

}
