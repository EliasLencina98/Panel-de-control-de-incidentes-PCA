@extends('layouts.panel')

@section('titulo', 'Nuevo chofer')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
@endpush

@section('contenido')
    <section class="gestion">
        <div class="panel-tarjeta gestion-encabezado">
            <h1>Registrar nuevo chofer</h1>

            <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                Volver al listado
            </a>
        </div>

        <div class="panel-tarjeta gestion-formulario">

            @if ($errors->any())
                <ul class="gestion-errores">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('choferes.store') }}" method="POST">
                @csrf

                <div class="gestion-campos">
                    <div>
                        <label for="nombre">Nombre y apellido</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="ej: Luis Alvarez" maxlength="255" required>
                    </div>

                    <div>
                        <label for="dni">DNI</label>
                        <input type="number" id="dni" name="dni" value="{{ old('dni') }}" maxlength="8" placeholder="ej: 12345678">
                    </div>

                    <div>
                        <label for="fecha_nacimiento">Fecha de nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                    </div>

                    <div>
                        <label for="fecha_ingreso">Fecha de ingreso</label>
                        <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" max="{{ date('Y-m-d') }}" required>
                    </div>

                    <div>
                        <label for="puesto">Puesto</label>
                        <input type="text" id="puesto" name="puesto" value="{{ old('puesto') }}" maxlength="100" placeholder="ej: camionero, transporte de pasajeros, chofer" required>
                    </div>

                    <div>
                        <label for="licencia">Licencia</label>
                            <select id="licencia" name="licencia" value="{{ old('licencia') }}" required>
                                <option value="B1">B1</option>
                                <option value="B2">B2</option>
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                                <option value="D1">D1</option>
                                <option value="D2">D2</option>
                                <option value="D3">D3</option>
                                <option value="E1">E1</option>
                            </select>
                    </div>
                </div>

                <div class="gestion-formulario-acciones">
                    <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                        Cancelar
                    </a>

                    <button type="submit" class="panel-boton gestion-boton-azul">
                        Guardar chofer
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection