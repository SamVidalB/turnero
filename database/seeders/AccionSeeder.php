<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Accion;
use Illuminate\Support\Facades\DB;

class AccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opcional: limpiar la tabla antes de sembrar, útil durante el desarrollo.
        // Asegúrate de que esto es seguro para tu entorno.
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Desactivar revisión de claves foráneas
        // Accion::truncate(); // Usar truncate si no hay claves foráneas o se desactivan temporalmente
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Reactivar revisión de claves foráneas
        // Alternativamente, eliminar uno por uno si hay pocas acciones y se quiere evitar desactivar FKC:
        // Accion::query()->delete();


        $acciones = [
            // Módulo Usuarios
            ['nombre' => 'Ver Listado de Usuarios', 'ruta' => 'usuarios.index', 'modulo' => 'Usuarios'],
            ['nombre' => 'Crear Nuevo Usuario', 'ruta' => 'usuarios.create', 'modulo' => 'Usuarios'],
            ['nombre' => 'Guardar Nuevo Usuario', 'ruta' => 'usuarios.store', 'modulo' => 'Usuarios'],
            ['nombre' => 'Editar Usuario Existente', 'ruta' => 'usuarios.edit', 'modulo' => 'Usuarios'],
            ['nombre' => 'Actualizar Usuario Existente', 'ruta' => 'usuarios.update', 'modulo' => 'Usuarios'],
            ['nombre' => 'Eliminar/Restaurar Usuario', 'ruta' => 'usuarios.destroy', 'modulo' => 'Usuarios'],
            ['nombre' => 'Ver Papelera de Usuarios', 'ruta' => 'usuarios.trash', 'modulo' => 'Usuarios'],
            ['nombre' => 'Gestionar Permisos de Usuarios', 'ruta' => 'usuarios.updatePermissions', 'modulo' => 'Usuarios'],

            // Módulo Aseguradores
            ['nombre' => 'Ver Listado de Aseguradores', 'ruta' => 'aseguradores.index', 'modulo' => 'Aseguradores'],
            ['nombre' => 'Crear Nuevo Asegurador', 'ruta' => 'aseguradores.create', 'modulo' => 'Aseguradores'],
            ['nombre' => 'Guardar Nuevo Asegurador', 'ruta' => 'aseguradores.store', 'modulo' => 'Aseguradores'],
            ['nombre' => 'Editar Asegurador Existente', 'ruta' => 'aseguradores.edit', 'modulo' => 'Aseguradores'],
            ['nombre' => 'Actualizar Asegurador Existente', 'ruta' => 'aseguradores.update', 'modulo' => 'Aseguradores'],
            ['nombre' => 'Eliminar/Restaurar Asegurador', 'ruta' => 'aseguradores.destroy', 'modulo' => 'Aseguradores'],
            ['nombre' => 'Ver Papelera de Aseguradores', 'ruta' => 'aseguradores.trash', 'modulo' => 'Aseguradores'],

            // Módulo Categorías
            ['nombre' => 'Ver Listado de Categorías', 'ruta' => 'categorias.index', 'modulo' => 'Categorías'],
            ['nombre' => 'Crear Nueva Categoría', 'ruta' => 'categorias.create', 'modulo' => 'Categorías'],
            ['nombre' => 'Guardar Nueva Categoría', 'ruta' => 'categorias.store', 'modulo' => 'Categorías'],
            ['nombre' => 'Editar Categoría Existente', 'ruta' => 'categorias.edit', 'modulo' => 'Categorías'],
            ['nombre' => 'Actualizar Categoría Existente', 'ruta' => 'categorias.update', 'modulo' => 'Categorías'],
            ['nombre' => 'Eliminar/Restaurar Categoría', 'ruta' => 'categorias.destroy', 'modulo' => 'Categorías'],
            ['nombre' => 'Ver Papelera de Categorías', 'ruta' => 'categorias.trash', 'modulo' => 'Categorías'],

            // Módulo Sedes
            ['nombre' => 'Ver Listado de Sedes', 'ruta' => 'sedes.index', 'modulo' => 'Sedes'],
            ['nombre' => 'Crear Nueva Sede', 'ruta' => 'sedes.create', 'modulo' => 'Sedes'],
            ['nombre' => 'Guardar Nueva Sede', 'ruta' => 'sedes.store', 'modulo' => 'Sedes'],
            ['nombre' => 'Editar Sede Existente', 'ruta' => 'sedes.edit', 'modulo' => 'Sedes'],
            ['nombre' => 'Actualizar Sede Existente', 'ruta' => 'sedes.update', 'modulo' => 'Sedes'],
            ['nombre' => 'Eliminar/Restaurar Sede', 'ruta' => 'sedes.destroy', 'modulo' => 'Sedes'],
            ['nombre' => 'Ver Papelera de Sedes', 'ruta' => 'sedes.trash', 'modulo' => 'Sedes'],

            // Módulo Pacientes
            ['nombre' => 'Ver Listado de Pacientes', 'ruta' => 'pacientes.index', 'modulo' => 'Pacientes'],
            ['nombre' => 'Crear Nuevo Paciente', 'ruta' => 'pacientes.create', 'modulo' => 'Pacientes'],
            ['nombre' => 'Guardar Nuevo Paciente', 'ruta' => 'pacientes.store', 'modulo' => 'Pacientes'],
            ['nombre' => 'Editar Paciente Existente', 'ruta' => 'pacientes.edit', 'modulo' => 'Pacientes'],
            ['nombre' => 'Actualizar Paciente Existente', 'ruta' => 'pacientes.update', 'modulo' => 'Pacientes'],
            ['nombre' => 'Eliminar/Restaurar Paciente', 'ruta' => 'pacientes.destroy', 'modulo' => 'Pacientes'],
            ['nombre' => 'Ver Papelera de Pacientes', 'ruta' => 'pacientes.trash', 'modulo' => 'Pacientes'],

            // Módulo Turnos (Ejemplos)
            // ['nombre' => 'Ver Listado de Turnos', 'ruta' => 'turnos.index', 'modulo' => 'Turnos'],
            // ['nombre' => 'Agendar Nuevo Turno', 'ruta' => 'turnos.create', 'modulo' => 'Turnos'],
            // ['nombre' => 'Guardar Nuevo Turno', 'ruta' => 'turnos.store', 'modulo' => 'Turnos'],
            // ['nombre' => 'Editar Turno Existente', 'ruta' => 'turnos.edit', 'modulo' => 'Turnos'],
            // ['nombre' => 'Actualizar Turno Existente', 'ruta' => 'turnos.update', 'modulo' => 'Turnos'],
            // ['nombre' => 'Cancelar Turno', 'ruta' => 'turnos.destroy', 'modulo' => 'Turnos'], // Asumiendo destroy para cancelar
        ];

        // Para evitar problemas con claves foráneas al limpiar, es mejor borrar y luego insertar.
        // O manejarlo con DB::transaction si es más complejo.
        Accion::query()->delete(); // Elimina todas las acciones existentes

        foreach ($acciones as $accionData) {
            Accion::create($accionData);
        }
    }
}
