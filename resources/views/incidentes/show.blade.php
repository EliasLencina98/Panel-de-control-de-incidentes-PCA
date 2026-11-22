@extends('layouts.panel')

@section('titulo', 'Detalle del incidente')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
@endpush

@section('contenido')
    <section class="gestion">

        <div class="panel-tarjeta gestion-encabezado">
            <div>
                <h1>Incidente #{{ $incidente->id }}</h1>

                <p class="panel-texto-secundario">
                    {{ ucfirst($incidente->tipo) }}
                </p>
            </div>

            <div class="gestion-acciones">
                <a href="{{ route('incidentes.index') }}" class="panel-boton gestion-boton-secundario">
                    Volver al listado
                </a>

                <a href="{{ route('incidentes.edit', $incidente->id) }}" class="panel-boton panel-boton-verde">
                    Modificar
                </a>
            </div>
        </div>


        <div class="panel-tarjeta gestion-detalle">
            <h2>Datos generales</h2>

            <dl class="detalle-datos">
                @foreach ([
                    'Fecha' => $incidente->fecha,
                    'Hora' => $incidente->hora ? substr($incidente->hora, 0, 5) : 'Sin informar',
                    'Gravedad' => ucfirst($incidente->gravedad),
                    'Estado' => ucfirst($incidente->estado),
                    'Heridos' => $incidente->heridos,
                    'Fallecidos' => $incidente->fatalidad,
                    'Chofer' => $incidente->chofer?->nombre ?? 'Sin asignar',
                    'Vehículo' => $incidente->vehiculo?->patente ?? 'Sin asignar',
                    'Clima' => $incidente->clima ?? 'Sin informar',
                    'Estado de calzada' => $incidente->estado_calzada ?? 'Sin informar',
                    'Latitud' => $incidente->latitud,
                    'Longitud' => $incidente->longitud
                    ] as $etiqueta => $valor)
                    <div>
                        <dt>{{ $etiqueta }}</dt>
                        <dd>{{ $valor }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        <div class="panel-tarjeta gestion-detalle">
            <h2>Ubicación y seguimiento</h2>

            <dl class="detalle-datos detalle-datos-completos">
                <div>
                    <dt>Ubicación</dt>
                    <dd>{{ $incidente->ubicacion }}</dd>
                </div>

                <div>
                    <dt>Descripción</dt>
                    <dd>{{ $incidente->descripcion }}</dd>
                </div>

                <div>
                    <dt>Causa raíz</dt>
                    <dd>
                        {{ $incidente->causa_raiz ?? 'Pendiente de investigación' }}
                    </dd>
                </div>

                <div>
                    <dt>Acción correlativa</dt>
                    <dd>
                        {{ $incidente->accion_correlativa ?? 'Sin informar' }}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="panel-tarjeta gestion-detalle">
            <h2>Responsables de investigación</h2>

            <div class="responsables-listado">
                @forelse ($incidente->responsables as $responsable)
                    <article class="gestion-registro">

                        <div class="gestion-registro-contenido">

                            <h3>{{ $responsable->name }}</h3>
                            <p>DNI: {{ $responsable->dni }}</p>
                            <p>Asignado el: {{ $responsable->pivot->fecha_asignacion }} </p>

                        </div>

                        <!-- Solo administrador -->
                        @can("gestionar-responsables")
                            <form
                                action="{{ route('incidentes.responsables.destroy', [$incidente->id, $responsable->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="panel-boton gestion-boton-rojo">Quitar asignación</button>
                            </form>
                        @endcan

                    </article>

                @empty
                    <p class="panel-texto-secundario">Este incidente todavía no tiene responsables asignados.</p>

                @endforelse
            </div>

            <!--Solo administrador-->
            @can("gestionar-responsables")

                <div class="responsables-asignacion">
                    <h3>Asignar responsable</h3>

                    @error('usuario_id')
                        <p class="gestion-errores">{{ $message }}</p>
                    @enderror

                    @if ($usuariosDisponibles->isNotEmpty())
                        <form action="{{ route('incidentes.responsables.store', $incidente->id) }}" method="POST">
                            @csrf

                            <div class="gestion-campos">
                                <div>
                                    <label for="usuario_id">Usuario</label>
                                    <select id="usuario_id" name="usuario_id" required>
                                        <option value="">Seleccionar usuario</option>
                                        @foreach ($usuariosDisponibles as $usuario)
                                            <option value="{{ $usuario->id }}" @selected(old('usuario_id') == $usuario->id)>
                                                {{ $usuario->name }} - DNI {{ $usuario->dni }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="gestion-formulario-acciones">
                                <button type="submit" class="panel-boton gestion-boton-azul">
                                    Asignar responsable
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="panel-texto-secundario">Todos los usuarios están asignados a este incidente.</p>
                    @endif
                </div>

            @endcan
        </div>

    </section>
@endsection