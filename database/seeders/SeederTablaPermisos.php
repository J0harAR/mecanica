<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//Spatie
use Spatie\Permission\Models\Permission;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos=[
            //Usuarios       
            'ver-usuarios',
            'crear-usuarios',
            'editar-usuarios',
            'borrar-usuarios',
            //Roles
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'borrar-rol',

            //Alumnos
            'ver-alumnos',
            'crear-alumnos',
            'editar-alumnos',
            'borrar-alumnos',

            //Asigatura
            'ver-asignaturas',
            'crear-asignatura',
            'editar-asignatura',
            'borrar-asignatura',

            //Docente

            'ver-docentes',
            'crear-docente',
            'ver-docente',
            'editar-docente',
            'borrar-docente',
            'asignar-grupos-docente',
            'eliminar-grupos-docente',
            
            //Grupos
            'ver-grupos',
            'crear-grupo',
            'borrar-grupo',

            //Inventario
            'ver-inventario',   
            'crear-articulo',        
            'borrar-inventario',
           

            //Herramientas
            'ver-herramientas',
            'crear-herramienta',
            'editar-herramienta',
            'borrar-herramienta',
            
            //Insumos      
            'ver-insumos',
            'crear-insumo',
            'editar-insumo',
            'borrar-insumo',

            //Maquinaria
            'ver-maquinarias',
            'crear-maquinaria',
            'editar-maquinaria',
            'asignar-insumos-maquinaria',
            'borrar-maquinaria',

            //Mantenimiento
            'ver-mantenimientos',
            'crear-mantenimiento',
            'borrar-mantenimiento',

            //Periodo
            'ver-periodos',
            'crear-periodo',
            'editar-periodo',
            'borrar-periodo',
            
            //Practicas
            'ver-practicas',
            'crear-practica',
            'ver-practica',
            'editar-practica',
            'borrar-practica',
            'completar-practica',
            'crear-practica-alumno',
          

            //Prestamos
            'ver-prestamos',
            'crear-prestamo',
            'editar-prestamo',
            'borrar-prestamo',
            'finalizar-prestamo',

            //Reportes
            'generar_reporte_prestamo',
            'generar_reporte_inventario',
            'generar_reporte_herramientas',
            'generar_reporte_maquinaria',
            'generar_reporte_insumos',
            'generar_reporte_practicas',

            //Lecturas
            'ver-lecturas',
            'crear-lectura',

        ];
        foreach($permisos as $permiso){
            Permission::create(['name'=>$permiso]);
            

        }
    }
}
