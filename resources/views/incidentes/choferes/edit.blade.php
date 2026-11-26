@extends('layouts.panel')

@section('titulo', 'Editar chofer')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
@endpush

@section('contenido')
<section class="gestion">
    <div class="panel-tarjeta gestion-encabezado">
        <h1>Editar chofer</h1>

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

        <form action="{{ route('choferes.update', $chofer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="gestion-campos">
                <div>
                    <label for="nombre">Nombre y apellido</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $chofer->nombre) }}" maxlength="255" required>
                </div>

                <div>
                    <label for="dni">DNI</label>
                    <input type="number" id="dni" name="dni" value="{{ old('dni', $chofer->dni) }}" maxlength="8" required>
                </div>

                <div>
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $chofer->fecha_nacimiento) }}" required>
                </div>

                <div>
                    <label for="fecha_ingreso">Fecha de ingreso</label>
                    <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso', $chofer->fecha_ingreso) }}" max="{{ date('Y-m-d') }}" required>
                </div>

                <div>
                    <label for="puesto">Puesto</label>
                    <input type="text" id="puesto" name="puesto" value="{{ old('puesto', $chofer->puesto) }}" maxlength="100" required>
                </div>

                <div>
                    <label for="licencia">Licencia</label>
                    <select id="licencia" name="licencia" required>
                        <option value="" disabled {{ old('licencia', $chofer->licencia) ? '' : 'selected' }}>Seleccione una licencia</option>
                        <option value="B1" {{ old('licencia', $chofer->licencia) == 'B1' ? 'selected' : '' }}>B1</option>
                        <option value="B2" {{ old('licencia', $chofer->licencia) == 'B2' ? 'selected' : '' }}>B2</option>
                        <option value="C1" {{ old('licencia', $chofer->licencia) == 'C1' ? 'selected' : '' }}>C1</option>
                        <option value="C2" {{ old('licencia', $chofer->licencia) == 'C2' ? 'selected' : '' }}>C2</option>
                        <option value="D1" {{ old('licencia', $chofer->licencia) == 'D1' ? 'selected' : '' }}>D1</option>
                        <option value="D2" {{ old('licencia', $chofer->licencia) == 'D2' ? 'selected' : '' }}>D2</option>
                        <option value="D3" {{ old('licencia', $chofer->licencia) == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="E1" {{ old('licencia', $chofer->licencia) == 'E1' ? 'selected' : '' }}>E1</option>
                    </select>
                </div>
            </div>

            <div class="gestion-formulario-acciones">
                <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                    Cancelar
                </a>

                <button type="submit" class="panel-boton gestion-boton-azul">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</section>
@endsection