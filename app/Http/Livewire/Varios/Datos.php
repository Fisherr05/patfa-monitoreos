<?php

namespace App\Http\Livewire\Varios;

use App\Models\Variedad;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Exports\DatosExport;
use Maatwebsite\Excel\Facades\Excel;


class Datos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    private $pagination = 5;
    public $cant;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        /*$data=DB::table('users')
        ->join()*/

        $datos = DB::table('monitoreos')
        ->join('estudios', 'estudios.id', '=', 'monitoreos.idEstudio')
        ->join('datos', 'monitoreos.id', '=', 'datos.idMonitoreo')
        ->join('finca_variedad', 'estudios.idFv', '=', 'finca_variedad.id')
        ->join('fincas', 'finca_variedad.finca_id', '=', "fincas.id")
        ->join('variedads','finca_variedad.variedad_id','=','variedads.id')
        ->join('plantas','datos.idPlanta', '=', 'plantas.id')
        ->join('zonas','fincas.idZona', '=','zonas.id')
        ->join('parroquias','zonas.idParroquia','=','parroquias.id')
        ->join('cantons','parroquias.idCanton','=','cantons.id')
        ->select('plantas.codigo as planta','datos.fruto as fruto','datos.incidencia as incidencia','datos.severidad as severidad','fincas.nombreFinca as finca','monitoreos.fechaEjecucion as fecha','fincas.densidad as densidad','cantons.nombre as canton','parroquias.nombre as parroquia','variedads.descripcion as variedad', 'monitoreos.estado')
        //->where('monitoreos.idTecnico',$id)
        //->where('monitoreos.estado',$si)
        ->paginate($this->pagination);//Ya funciona xD


        return view('livewire.varios.datos', compact('datos'))
        ->extends('adminlte::page');
    }
    // public function vista($id)
    // {
    //     $si='si';
    //     $datos = DB::table('monitoreos')
    //     ->join('estudios', 'estudios.id', '=', 'monitoreos.idEstudio')
    //     ->join('datos', 'monitoreos.id', '=', 'datos.idMonitoreo')
    //     ->join('finca_variedad', 'estudios.idFv', '=', 'finca_variedad.id')
    //     ->join('fincas', 'finca_variedad.finca_id', '=', "fincas.id")
    //     ->join('variedads','finca_variedad.variedad_id','=','variedads.id')
    //     ->join('plantas','datos.idPlanta', '=', 'plantas.id')
    //     ->join('zonas','fincas.idZona', '=','zonas.id')
    //     ->join('parroquias','zonas.idParroquia','=','parroquias.id')
    //     ->join('cantons','parroquias.idCanton','=','cantons.id')
    //     ->select('plantas.codigo as planta','datos.fruto as fruto','datos.incidencia as incidencia','datos.severidad as severidad','fincas.nombreFinca as finca','monitoreos.fechaEjecucion as fecha','fincas.densidad as densidad','cantons.nombre as canton','parroquias.nombre as parroquia','variedads.descripcion as variedad', 'monitoreos.estado')
    //     //->where('monitoreos.idTecnico',$id)
    //     //->where('monitoreos.estado',$si)
        
    //     ->get();//Ya funciona xD
    //     return view('livewire.varios.datos',compact('datos'))->extends('adminlte::page');;
    // }

    public function exportExcel()
    {
        return Excel::download( new DatosExport, 'datos.xlsx' );
    }
}
