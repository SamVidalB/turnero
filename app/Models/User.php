<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios'; // Especificar el nombre de la tabla si es diferente de 'users'

    protected $fillable = [
        'nombre',
        'documento',
        'email',
        'password',
        'rol',
        'estado'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Asegurar que la contraseña se hashea automáticamente
    ];

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }

    /**
     * El registro de permisos asociado al usuario.
     */
    public function permiso()
    {
        return $this->hasOne(Permiso::class, 'usuario_id');
    }

    /**
     * Obtiene los IDs de las acciones permitidas para el usuario.
     * Retorna un array vacío si no tiene permisos asignados.
     */
    public function getAccionesPermitidasIdsAttribute(): array
    {
        return $this->permiso ? ($this->permiso->acciones ?? []) : [];
    }

    /**
     * Verifica si el usuario tiene una acción específica por su ID.
     */
    public function hasAccionId(int $accionId): bool
    {
        return in_array($accionId, $this->getAccionesPermitidasIdsAttribute());
    }

    /**
     * Verifica si el usuario tiene una acción específica por su nombre de ruta.
     * Esto requiere cargar la acción desde la BD.
     */
    public function hasAccionRuta(string $rutaNombre): bool
    {
        $accion = Accion::where('ruta', $rutaNombre)->first();
        if (!$accion) {
            return false;
        }
        return $this->hasAccionId($accion->id);
    }
}
