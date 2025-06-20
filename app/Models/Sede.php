<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'municipio',
        'estado'
    ];

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }
    
}
