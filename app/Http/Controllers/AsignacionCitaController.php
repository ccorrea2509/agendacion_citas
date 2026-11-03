<?php

namespace App\Http\Controllers;

use App\Models\AsignacionCita;
use App\Models\Procedimiento;
use App\Models\Servicio;
use App\Models\Vehiculo;
use App\Models\PuestoTrabajo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;


class AsignacionCitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function disponibles(Request $request)
    {
        
        $request->validate([
            'fecha' => ['required', 'date_format:Y-m-d'],
        ]);
        /*
        $fecha = $request->fecha;
        $citas = AsignacionCita::whereDate('cita_fecha', $fecha)
            ->get()
            ->map(function ($cita) {
                $inicio = Carbon::parse($cita->cita_fecha);

                $fin = $inicio->copy()
                    ->addMinutes($cita->tiempo_aproximado);

                $cita->hora_inicio = $inicio->format('h:i A');
                $cita->hora_fin = $fin->format('h:i A');

                return $cita;
            });
        dd($citas);
        $puestosDisponibles = PuestoTrabajo::all();
        //dd($puestosDisponibles);
        return response()->json([
            'citas' => $citas,
            'fecha' => $fecha,
            'puestosDisponibles' => $puestosDisponibles,
        ]);*/

        $fecha = $request->fecha;

        $inicioJornada = Carbon::parse($fecha . ' 08:00:00');
        $finJornada = Carbon::parse($fecha . ' 18:00:00');

        // Obtener todos los puestos de trabajo
        $puestosDisponibles = PuestoTrabajo::all();

        // Obtener las citas de la fecha seleccionada
        $citas = AsignacionCita::whereDate('cita_fecha', $fecha)->get();

        $resultado = $puestosDisponibles->map(function ($puesto) use (
            $citas, $inicioJornada, $finJornada
        ) {

            // Filtrar las citas asignadas a este puesto
            $citasPuesto = $citas
                ->where('puesto_asignado', $puesto->id)
                ->sortBy('cita_fecha');

            $horariosOcupados = $citasPuesto->map(function ($cita) {

                $inicio = Carbon::parse($cita->cita_fecha);

                $fin = $inicio->copy()
                    ->addMinutes($cita->tiempo_aproximado);

                return [
                    'inicio' => $inicio,
                    'fin' => $fin
                ];
            });

            // Calcular horarios disponibles
            $disponibles = [];
            $cursor = $inicioJornada->copy();

            foreach ($horariosOcupados as $horario) {

                if ($horario['inicio']->greaterThan($cursor)) {
                    $disponibles[] = [
                        'inicio' => $cursor->format('H:i'),
                        'fin' => $horario['inicio']->format('H:i')
                    ];
                }

                if ($horario['fin']->greaterThan($cursor)) {
                    $cursor = $horario['fin']->copy();
                }
            }

            // Agregar disponibilidad después de la última cita
            if ($cursor->lessThan($finJornada)) {
                $disponibles[] = [
                    'inicio' => $cursor->format('H:i'),
                    'fin' => $finJornada->format('H:i')
                ];
            }

            return [
                'puesto' => $puesto,
                'horarios_disponibles' => $disponibles
            ];
        });
        //dd($resultado);
        return response()->json([
            'citas' => $citas,
            'fecha' => $fecha,
            //'puestosDisponibles' => $puestosDisponibles,
            'horarios_disponibles' => $resultado
        ]);
        //return Inertia::render('dashboard/agendacion_cita/disponibles', compact('citas','fecha','puestosDisponibles'));
    }
    public function index()
    {
        
        $citas = AsignacionCita::with('vehiculo', 'usuario_creador')->get();
        
        return Inertia::render('dashboard/agendacion_cita/index', compact('citas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servicios=Servicio::all();
        $vehiculos = Vehiculo::all();
        return Inertia::render('dashboard/agendacion_cita/create', compact('servicios', 'vehiculos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd(auth()->user()->name);
        $request->validate([
            'fecha' => 'required',
            'hora' => 'required',
            'puesto_trabajo' => 'required',
            'servicios' => 'required',
            'vehiculo' => 'required',
        ]);

        //dd($request->all());
        $cita = new AsignacionCita();
        $cita->cita_fecha = $request->fecha . ' ' . $request->hora;
        $cita->id_vehiculo = $request->vehiculo; // Asignar un ID de vehículo válido según tu lógica
        $cita->id_user = auth()->user()->id; // Asignar el ID del usuario autenticado
        $cita->tiempo_aproximado = $request->tiempo;
        $cita->puesto_asignado=$request->puesto_trabajo; // Asignar un tiempo aproximado según tu lógica
        $cita->save();
        
        foreach ($request->servicios as $servicioId) {
            
            $procedimiento=new Procedimiento();
            $procedimiento->id_cita=$cita->id;
            $procedimiento->id_puesto_trabajo=$request->puesto_trabajo;
            $procedimiento->id_personal=auth()->user()->id; // Asignar un ID de personal válido según tu lógica
            $procedimiento->id_user=auth()->user()->id; // Asignar el ID del usuario autenticado

            $servicio=Servicio::find($servicioId);

            $procedimiento->precio=$servicio->precio; // Asignar un precio según tu lógica
            $procedimiento->id_servicio=$servicioId;
            $procedimiento->save();
        }
        
        return redirect()->route('AsignacionCita.index')->with('success', 'Cita creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AsignacionCita $asignacionCita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AsignacionCita $asignacionCita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AsignacionCita $asignacionCita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AsignacionCita $asignacionCita)
    {
        //
    }
}
