<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        $incidentes = $usuario->incidentesInvestigados()
            ->withPivot('fecha_asignacion')
            ->orderByPivot('fecha_asignacion', 'desc')
            ->get();

        return view('incidentes.perfil.index', compact('usuario', 'incidentes'));
    }
}