<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asegurador extends Model
{
    use HasFactory;

    protected $table = 'aseguradores';

    protected $fillable = ['nit', 'nombre' ];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }

}
