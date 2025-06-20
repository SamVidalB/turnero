<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'acciones',
    ];

    public function usuario()
    {
        return $this->belongsToMany(Usuario::class);
    }

}
