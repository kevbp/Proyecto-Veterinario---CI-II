<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -----------
        // PERMISOS
        // -----------

        // Limpiar permisos con guard 'web' creados por error en ejecuciones anteriores
        Permission::where('guard_name', 'web')->delete();

        $permisos = [
            'crear usuarios', 'editar usuarios', 'eliminar usuarios', 'ver usuarios',
            'crear mascotas', 'editar mascotas', 'eliminar mascotas', 'ver mascotas',
            'crear citas', 'editar citas', 'eliminar citas', 'ver citas',
            'crear consultas', 'editar consultas', 'eliminar consultas', 'ver consultas',
            'crear horarios', 'editar horarios', 'eliminar horarios', 'ver horarios',
            'crear recetas', 'editar recetas', 'eliminar recetas', 'ver recetas',
            'crear analisis', 'editar analisis', 'eliminar analisis', 'ver analisis',
            'crear resultadoanalisis', 'editar resultadoanalisis', 'eliminar resultadoanalisis', 'ver resultadoanalisis',
            'crear instrumentos', 'editar instrumentos', 'eliminar instrumentos', 'ver instrumentos',
            'crear medicamentos', 'editar medicamentos', 'eliminar medicamentos', 'ver medicamentos',
            'crear inventario', 'editar inventario', 'eliminar inventario', 'ver inventario',
            'crear pagos', 'editar pagos', 'eliminar pagos', 'ver pagos',
            'crear boletas', 'editar boletas', 'eliminar boletas', 'ver boletas',
            'crear reportes', 'editar reportes', 'eliminar reportes', 'ver reportes',
            'crear personal', 'editar personal', 'eliminar personal', 'ver personal',
            'crear propietarios', 'editar propietarios', 'eliminar propietarios', 'ver propietarios',
            'crear historiales', 'editar historiales', 'eliminar historiales', 'ver historiales',
            'crear desparasitaciones', 'editar desparasitaciones', 'eliminar desparasitaciones', 'ver desparasitaciones',
            'crear vacunas', 'editar vacunas', 'eliminar vacunas', 'ver vacunas',
            'crear especies', 'editar especies', 'eliminar especies', 'ver especies',
            'crear razas', 'editar razas', 'eliminar razas', 'ver razas',
            'crear tipos-examenes', 'editar tipos-examenes', 'eliminar tipos-examenes', 'ver tipos-examenes',
            'crear condiciones', 'editar condiciones', 'eliminar condiciones', 'ver condiciones',
            'crear alergias', 'editar alergias', 'eliminar alergias', 'ver alergias',
            'crear examenes', 'editar examenes', 'eliminar examenes', 'ver examenes',
            'crear resultados', 'editar resultados', 'eliminar resultados', 'ver resultados',
            'crear campanias', 'editar campanias', 'eliminar campanias', 'ver campanias',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'api']);
        }

        // ROLES
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $gestorRole = Role::firstOrCreate(['name' => 'gestor', 'guard_name' => 'api']);
        $recepcionistaRole = Role::firstOrCreate(['name' => 'recepcionista', 'guard_name' => 'api']);
        $veterinarioRole = Role::firstOrCreate(['name' => 'veterinario', 'guard_name' => 'api']);
        $propietarioRole = Role::firstOrCreate(['name' => 'propietario', 'guard_name' => 'api']);

        // ASIGNAR PERMISOS A ROLES
        $adminRole->givePermissionTo(Permission::where('guard_name', 'api')->get());
        $gestorRole->syncPermissions([
            'ver usuarios', 'crear usuarios', 'editar usuarios',
            'ver propietarios', 'crear propietarios', 'editar propietarios',
            'ver mascotas', 'crear mascotas', 'editar mascotas', 'eliminar mascotas',
            'ver citas', 'crear citas', 'editar citas', 'eliminar citas',
            'ver consultas', 'crear consultas', 'editar consultas', 'eliminar consultas',
            'ver horarios', 'crear horarios', 'editar horarios', 'eliminar horarios',
            'ver recetas', 'crear recetas', 'editar recetas', 'eliminar recetas',
            'ver analisis', 'crear analisis', 'editar analisis', 'eliminar analisis',
            'ver resultadoanalisis', 'crear resultadoanalisis', 'editar resultadoanalisis', 'eliminar resultadoanalisis',
            'ver instrumentos', 'crear instrumentos', 'editar instrumentos', 'eliminar instrumentos',
            'ver medicamentos', 'crear medicamentos', 'editar medicamentos', 'eliminar medicamentos',
            'ver inventario', 'crear inventario', 'editar inventario', 'eliminar inventario',
            'ver pagos', 'crear pagos', 'editar pagos', 'eliminar pagos',
            'ver boletas', 'crear boletas', 'editar boletas', 'eliminar boletas',
            'ver reportes', 'crear reportes', 'editar reportes', 'eliminar reportes',
            'ver personal', 'crear personal', 'editar personal', 'eliminar personal',
            'ver historiales', 'crear historiales', 'editar historiales', 'eliminar historiales',
            'ver desparasitaciones', 'crear desparasitaciones', 'editar desparasitaciones', 'eliminar desparasitaciones',
            'ver vacunas', 'crear vacunas', 'editar vacunas', 'eliminar vacunas',
            'ver tipos-examenes', 'crear tipos-examenes', 'editar tipos-examenes', 'eliminar tipos-examenes',
            'ver condiciones', 'crear condiciones', 'editar condiciones', 'eliminar condiciones',
            'ver alergias', 'crear alergias', 'editar alergias', 'eliminar alergias',
            'ver examenes', 'crear examenes', 'editar examenes', 'eliminar examenes',
            'ver resultados', 'crear resultados', 'editar resultados', 'eliminar resultados',
            'ver campanias', 'crear campanias', 'editar campanias', 'eliminar campanias',
        ]);
        $recepcionistaRole->syncPermissions([
            'ver mascotas', 'editar mascotas',
            'ver personal',
            'ver propietarios', 'crear propietarios', 'editar propietarios',
            'ver citas', 'crear citas', 'editar citas', 'eliminar citas',
            'ver consultas',
            'ver historiales',
            'ver horarios', 'crear horarios', 'editar horarios', 'eliminar horarios',
            'ver recetas',
            'ver analisis',
            'ver resultadoanalisis',
            'ver pagos',
            'ver boletas', 'crear boletas',
            'ver desparasitaciones', 'crear desparasitaciones', 'editar desparasitaciones', 'eliminar desparasitaciones',
            'ver vacunas', 'crear vacunas', 'editar vacunas', 'eliminar vacunas',
            'ver tipos-examenes',
            'ver condiciones', 'editar condiciones',
            'ver alergias', 'editar alergias',
            'ver examenes',
            'ver resultados',
            'ver campanias',
        ]);
        $veterinarioRole->syncPermissions([
            'ver mascotas', 'crear mascotas', 'editar mascotas',
            'ver propietarios', 'crear propietarios', 'editar propietarios',
            'ver citas',
            'ver consultas', 'crear consultas', 'editar consultas',
            'ver historiales',
            'ver horarios',
            'ver recetas', 'crear recetas', 'editar recetas', 'eliminar recetas',
            'ver analisis', 'crear analisis', 'editar analisis', 'eliminar analisis',
            'ver resultadoanalisis', 'crear resultadoanalisis', 'editar resultadoanalisis', 'eliminar resultadoanalisis',
            'ver instrumentos',
            'ver medicamentos',
            'ver inventario', 'crear inventario', 'editar inventario', 'eliminar inventario', // CHECAR ESTE PERMISO PARA ESTE ROL
            'ver pagos',
            'ver boletas',
            'ver desparasitaciones', 'crear desparasitaciones', 'editar desparasitaciones', 'eliminar desparasitaciones',
            'ver vacunas', 'crear vacunas', 'editar vacunas', 'eliminar vacunas',
            'ver tipos-examenes',
            'ver razas',
            'ver condiciones', 'crear condiciones', 'editar condiciones', 'eliminar condiciones',
            'ver alergias', 'crear alergias', 'editar alergias', 'eliminar alergias',
            'ver examenes', 'crear examenes', 'editar examenes', 'eliminar examenes',
            'ver resultados', 'crear resultados', 'editar resultados', 'eliminar resultados',
            'ver campanias',
        ]);
        $propietarioRole->syncPermissions([
            'ver mascotas', 'editar mascotas',
            'ver citas',
            'ver consultas',
            'ver historiales',
            'ver recetas',
            'ver resultadoanalisis',
            'ver boletas',
            'ver desparasitaciones',
            'ver vacunas',
            'ver tipos-examenes',
            'ver alergias',
            'ver condiciones',
            'ver examenes',
            'ver resultados',
            'ver campanias',
        ]);
    }
}
