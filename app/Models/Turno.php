<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefijo',
        'consecutivo',
        'paciente_id',
        'sede_id',
        'estado',
        'usuario_admision_id',
        'usuario_consulta_id',
        'usuario_postconsulta_id',
        'hora_admision',
        'hora_consulta',
        'hora_postconsulta',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function usuarioAdmision()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function usuarioConsulta()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function usuarioPostconsulta()
    {
        return $this->belongsTo(Usuario::class);
    }

}
