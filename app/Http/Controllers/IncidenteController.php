<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use App\Models\Vehiculo;
use App\Models\Chofer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class IncidenteController extends Controller
{

    public function index(): View
    {
        return view("incidentes.index");
    }


    public function create(): View
    {
        // Necesitamos mandar los vehículos y choferes a la vista para poblar los <select>
        $vehiculos = Vehiculo::all();
        $choferes = Chofer::all();

        return view("incidentes.create", compact("vehiculos", "choferes"));
    }


    public function show(Int $id): View
    {
        //Incidente con los implicados (chofer/vehiculos/responsables)
        $incidente = Incidente::with(['vehiculo', 'chofer', "responsables"])->findOrFail($id);

        //Solo usuarios sin asignacion a una causa
        $usuariosDisponibles = User::whereNotIn("id", $incidente->responsables->pluck("id"))->orderBy("name")->get();

        return view("incidentes.show", compact("incidente", "usuariosDisponibles"));
    }


    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "fecha" => ["date", "required", "date_format:Y-m-d"],
            "hora" => ["nullable", "date_format:H:i"],
            "tipo" => ["required", "in:colision/choque vehicular,impacto objeto fijo,vuelco,despiste,atropello,falla mecanica,principio de incendio/incendio,daño menor,casi incidente"],
            "fatalidad" => ["required", "integer", "min:0"],
            "heridos" => ["required", "integer", "min:0"],
            "ubicacion" => ["string", "required"],
            "latitud" => ["numeric", "required", "between:-90,90"],
            "longitud" => ["numeric", "required", "between:-180,180"],
            "estado_calzada" => ["nullable", "string"],
            "clima" => ["nullable", "string"],
            "descripcion" => ["string", "required"],
            "causa_raiz" => ["nullable", "in:Factor humano,Falla mecanica,Condiciones climaticas,Otro"],
            "accion_correlativa" => ["nullable", "string"],
            "estado" => ["required", "in:pendiente,en proceso,resuelto"],
            "vehiculo_id" => ["required", "exists:vehiculo,id"],
            "chofer_id" => ["required", "exists:chofer,id"],
        ]);

        // calculamos automaticamente la gravedad usando la matriz de gravedad
        $gravedadCalculada = Incidente::calcularGravedad(
            $validated["tipo"],
            $validated["heridos"],
            $validated["fatalidad"]
        );

        Incidente::create([
            "fecha" => $validated["fecha"],
            "hora" => $validated["hora"] ?? null,
            "tipo" => $validated["tipo"],
            "fatalidad" => $validated["fatalidad"],
            "heridos" => $validated["heridos"],
            "gravedad" => $gravedadCalculada, // 
            "ubicacion" => $validated["ubicacion"],
            "latitud" => $validated["latitud"],
            "longitud" => $validated["longitud"],
            "estado_calzada" => $validated["estado_calzada"] ?? null,
            "clima" => $validated["clima"] ?? null,
            "descripcion" => $validated["descripcion"],
            "causa_raiz" => $validated["causa_raiz"] ?? null,
            "accion_correlativa" => $validated["accion_correlativa"] ?? null,
            "estado" => $validated["estado"],
            "vehiculo_id" => $validated["vehiculo_id"],
            "chofer_id" => $validated["chofer_id"],
        ]);

        return redirect()->route("incidentes.index")
            ->with("mensaje", "¡Incidente cargado correctamente!");
    }



    public function edit(Int $id): View
    {
        $incidente = Incidente::findOrFail($id);
        $vehiculos = Vehiculo::all();
        $choferes = Chofer::all();

        return view("incidentes.edit", compact("incidente", "vehiculos", "choferes"));
    }



    public function update(Request $request, int $id): RedirectResponse
    {
        $incidente = Incidente::findOrFail($id);

        $datosValidados = $request->validate([
            "fecha" => ["date", "required", "date_format:Y-m-d"],
            "hora" => ["nullable", "date_format:H:i"],
            "tipo" => ["required", "in:colision/choque vehicular,impacto objeto fijo,vuelco,despiste,atropello,falla mecanica,principio de incendio/incendio,daño menor,casi incidente"],
            "fatalidad" => ["required", "integer", "min:0"],
            "heridos" => ["required", "integer", "min:0"],
            "ubicacion" => ["string", "required"],
            "latitud" => ["numeric", "required", "between:-90,90"],
            "longitud" => ["numeric", "required", "between:-180,180"],
            "estado_calzada" => ["nullable", "string"],
            "clima" => ["nullable", "string"],
            "descripcion" => ["string", "required"],
            "causa_raiz" => ["nullable", "in:Factor humano,Falla mecanica,Condiciones climaticas,Otro"],
            "accion_correlativa" => ["nullable", "string"],
            "estado" => ["required", "in:pendiente,en proceso,resuelto"],
            "vehiculo_id" => ["required", "exists:vehiculo,id"],
            "chofer_id" => ["required", "exists:chofer,id"],
        ]);

        // en caso que se modifique el tipo, heridos o fatalidad, volvemos a recalcular
        $datosValidados['gravedad'] = Incidente::calcularGravedad(
            $datosValidados["tipo"],
            $datosValidados["heridos"],
            $datosValidados["fatalidad"]
        );

        $incidente->update($datosValidados);

        return redirect()->route("incidentes.index")->with("mensaje", "¡Incidente modificado correctamente!");
    }



    public function destroy(int $id)
    {
        $incidente = Incidente::findOrFail($id);
        $incidente->delete();

        return response()->json([
            'mensaje' => 'Incidente eliminado correctamente.',
        ]);
    }



    //Mapa vistas
    public function obtenerDatos(Request $request)
    {

        $datosFiltros = $request->validate([
            "fecha_inicio" => ["nullable", "date_format:Y-m-d"],
            "fecha_fin" => ["nullable", "date_format:Y-m-d", Rule::when($request->filled("fecha_inicio"), "after_or_equal:fecha_inicio")],
            "gravedad" => ["nullable", "in:leve,moderado,grave,fatal"],
            "tipo" => ["nullable", "string"],
            "estado" => ["nullable", "in:pendiente,en proceso,resuelto"],
            "buscar" => ["nullable", "string", "max:100"],
        ]);

        $consulta = Incidente::with(['vehiculo', 'chofer']);

        if (!empty($datosFiltros["fecha_inicio"])) {
            $consulta->where("fecha", ">=", $datosFiltros["fecha_inicio"]);
        }

        if (!empty($datosFiltros["fecha_fin"])) {
            $consulta->where("fecha", "<=", $datosFiltros["fecha_fin"]);
        }

        if (!empty($datosFiltros["gravedad"])) {
            $consulta->where("gravedad", $datosFiltros["gravedad"]);
        }

        if (!empty($datosFiltros["tipo"])) {
            $consulta->where("tipo", $datosFiltros["tipo"]);
        }

        if (!empty($datosFiltros["estado"])) {
            $consulta->where("estado", $datosFiltros["estado"]);
        }

        if (!empty($datosFiltros["buscar"])) {
            $buscar = "%" . trim($datosFiltros["buscar"]) . "%";

            $consulta->where(function ($query) use ($buscar) {
                $query->where("tipo", "like", $buscar)
                ->orWhereHas("chofer", function ($chofer) use ($buscar) {
                        $chofer->where("nombre", "like", $buscar);
                })->orWhereHas("vehiculo", function ($vehiculo) use ($buscar) {
                        $vehiculo->where("patente", "like", $buscar);
                });
            });
        }


        $incidentes = (clone $consulta)->orderBy("fecha", "desc")->orderBy("id", "desc")->get();

        $total = (clone $consulta)->count();
        $leves = (clone $consulta)->where("gravedad", "leve")->count();
        $moderados = (clone $consulta)->where("gravedad", "moderado")->count();
        $graves = (clone $consulta)->where("gravedad", "grave")->count();
        $fatales = (clone $consulta)->where("gravedad", "fatal")->count();

        return response()->json([
            'incidentes' => $incidentes,
            'metricas' => [
                'total' => $total,
                'leves' => $leves,
                'moderados' => $moderados,
                'graves' => $graves,
                'fatales' => $fatales,
            ]
        ]);
    }


    //Acciones administrador
    public function asignarResponsable (Request $request, int $id)
    {
        $incidente = Incidente::findOrFail($id);

        $datos = $request->validate([
            "usuario_id" => "required|integer|exists:users,id", //debe existir su id en la base de datos
        ]);

        //Usuario asignado a incidente
        $asignado = $incidente->responsables()->where("users.id", $datos["usuario_id"])->exists();

        //Comprobacion de su asignacion
        if($asignado){
            return redirect()->route("incidentes.show", $id)->with("mensaje", "El usuario ya esta signado.");
        }

        //Asignamos usuario a incidente
        $incidente->responsables()->attach($datos["usuario_id"], ["fecha_asignacion" => now()->toDateString()]);

        return redirect()->route("incidentes.show", $id)->with("mensaje", "Responsable asigando correctamente");

    }



    public function quitarResponsable (int $id, int $usuario)
    {
        $incidente = Incidente::findOrFail($id);

        //Eliminacion de usuario como responsable (no de Users)
        $eliminado = $incidente->responsables()->detach($usuario); //1: asignado y 0: no esta asignado a ningun incidente 

        return redirect()->route("incidentes.show", $id)->with("mensaje", $eliminado ? 
        "El usuario fue removido correctamente." : "El usuario no estaba asignado al incidente.");
    }


    //Dashboard
    public function dashboard(): View
    {
        $tiposDisponibles = Incidente::query()->distinct()->orderBy("tipo")->pluck("tipo");

        $estadosDisponibles = Incidente::query()->distinct()->orderBy("estado")->pluck("estado");

        $gravedadDisponibles = ["leve", "moderado", "grave", "fatal"];

        return view("incidentes.dashboard", compact("tiposDisponibles", "estadosDisponibles", "gravedadDisponibles"));
    }

    public function dashboardDatos(Request $request) 
    {
        $filtros = $request->validate([
                "fecha_inicio" => ['nullable', 'date_format:Y-m-d'],
                "fecha_fin" => ['nullable','date_format:Y-m-d',
                    Rule::when( $request->filled('fecha_inicio'),'after_or_equal:fecha_inicio'),], //igual o posterior
                "gravedad" => ['nullable', 'in:leve,moderado,grave,fatal'],
                "tipo" => ['nullable', 'string'],
                "estado" => ['nullable', 'in:pendiente,en proceso,resuelto'],
                ]);

        $consulta = Incidente::query();

        if(!empty($filtros["fecha_inicio"])) {
            $consulta->where("fecha", ">=", $filtros["fecha_inicio"]);
        }

        if (!empty($filtros['fecha_fin'])) {
        $consulta->where('fecha', '<=', $filtros['fecha_fin']);
        }

        if (!empty($filtros['gravedad'])) {
            $consulta->where('gravedad', $filtros['gravedad']);
        }

        if (!empty($filtros['tipo'])) {
            $consulta->where('tipo', $filtros['tipo']);
        }

        if (!empty($filtros['estado'])) {
            $consulta->where('estado', $filtros['estado']);
        }


        //Cantidades por gravedad (Ej: Leve => 5)
        $porGravedad = (clone $consulta)->selectRaw("gravedad, COUNT(*) as total")->groupBy("gravedad")->pluck('total', 'gravedad');

        $leves = (int) ($porGravedad["leve"] ?? 0);
        $moderados = (int) ($porGravedad["moderado"] ?? 0);
        $graves = (int) ($porGravedad["grave"] ?? 0);
        $fatales = (int) ($porGravedad["fatal"] ?? 0);
        $total = (int) $porGravedad->sum();

        $kpis = [
            "total" => $total,
            "leves" => $leves,
            "moderados" => $moderados,
            "graves" => $graves,
            "fatales" => $fatales,
            "leves_pct" => $total > 0 ? round($leves * 100 / $total) : 0,
            "moderados_pct" => $total > 0 ? round($moderados * 100 / $total) : 0,
            "graves_pct" => $total > 0 ? round($graves * 100 / $total) : 0,
            "fatales_pct" => $total > 0 ? round($fatales * 100 / $total) : 0,
        ];


        //Agrupacion mensual para SQLITE
        $tendencia = (clone $consulta)->selectRaw("strftime('%Y-%m', fecha) as mes, COUNT(*) as total")
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        $porTipo = (clone $consulta)->selectRaw('tipo, COUNT(*) as total')->groupBy('tipo')->orderByDesc('total')->get();

        $incidentes = (clone $consulta)->whereNotNull('latitud')->whereNotNull('longitud')->get([
        'id', 'fecha', 'tipo', 'gravedad', 'estado','latitud', 'longitud', 'ubicacion', 'descripcion',]);

        return response()->json([
            "kpis" => $kpis,
            "tendencia" => $tendencia, 
            "por_gravedad" => [
                "leve" => $leves,
                "moderado" => $moderados, 
                "grave" => $graves, 
                "fatal" => $fatales
                ],
            "por_tipo" => $porTipo, 
            "incidentes" => $incidentes
        ]);

    }
    
}
