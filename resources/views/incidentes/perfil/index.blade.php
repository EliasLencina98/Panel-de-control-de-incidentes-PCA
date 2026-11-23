@extends('layouts.panel')

@push("styles")
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
@endpush

@section('contenido')

<div id="perfil-seccion" class="perfil-seccion">
    {{-- Encabezado del perfil --}}
    <div id="perfil-encabezado" class="perfil-encabezado">
        <div>
            <h1 id="perfil-titulo">Mi Perfil</h1>
            <p id="perfil-subtitulo" class="perfil-subtitulo">
                Información de tu cuenta y seguimiento de incidentes donde figurás como responsable.
            </p>
        </div>
    </div>

    {{-- Datos del usuario autenticado --}}
    <section id="perfil-datos-card" class="panel-tarjeta">
        <h2>Datos del usuario</h2>

        <div id="perfil-datos-grid" class="perfil-datos-grid">
            <div id="perfil-dato-nombre" class="perfil-dato-caja">
                <span class="perfil-dato-etiqueta">Nombre</span>
                <span id="perfil-valor-nombre" class="perfil-dato-valor">{{ $usuario->name }}</span>
            </div>

            <div id="perfil-dato-email" class="perfil-dato-caja">
                <span class="perfil-dato-etiqueta">Correo</span>
                <span id="perfil-valor-email" class="perfil-dato-valor">{{ $usuario->email }}</span>
            </div>

            <div id="perfil-dato-dni" class="perfil-dato-caja">
                <span class="perfil-dato-etiqueta">DNI</span>
                <span id="perfil-valor-dni" class="perfil-dato-valor">{{ $usuario->dni ?? 'No registrado' }}</span>
            </div>

            <div id="perfil-dato-rol" class="perfil-dato-caja">
                <span class="perfil-dato-etiqueta">Rol</span>
                <span id="perfil-valor-rol" class="perfil-dato-valor">{{ $usuario->rol ?? 'No asignado' }}</span>
            </div>
        </div>
    </section>

    {{-- Incidentes asignados como responsable --}}
    <section id="perfil-incidentes-card" class="panel-tarjeta">
        <div id="perfil-listado-cabecera" class="perfil-listado-cabecera">
            <h2>Incidentes asignados como responsable</h2>
            <span id="perfil-contador-incidentes" class="perfil-contador">{{ $incidentes->count() }}</span>
        </div>

        @if($incidentes->isEmpty())
            <div id="perfil-incidentes-vacio" class="perfil-vacio">
                No tenés incidentes asignados como responsable por el momento.
            </div>
        @else
            <div id="perfil-tabla-contenedor" class="perfil-tabla-contenedor">
                <table id="perfil-tabla-incidentes" class="perfil-tabla">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha incidente</th>
                            <th>Asignado el</th>
                            <th>Tipo</th>
                            <th>Gravedad</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="perfil-tabla-cuerpo">
                        @foreach($incidentes as $incidente)
                            @php
                                $gravedadSlug = strtolower($incidente->gravedad ?? '');
                                $estadoSlug = strtolower($incidente->estado ?? '');
                            @endphp
                            <tr id="perfil-incidente-fila-{{ $incidente->id }}">
                                <td id="perfil-incidente-id-{{ $incidente->id }}">{{ $incidente->id }}</td>
                                <td id="perfil-incidente-fecha-{{ $incidente->id }}">{{ $incidente->fecha }}</td>
                                <td id="perfil-incidente-asignado-{{ $incidente->id }}">{{ $incidente->pivot->fecha_asignacion ?? '-' }}</td>
                                <td id="perfil-incidente-tipo-{{ $incidente->id }}">{{ $incidente->tipo }}</td>
                                <td id="perfil-incidente-gravedad-{{ $incidente->id }}">
                                    <span class="perfil-badge perfil-badge-neutro perfil-gravedad-{{ $gravedadSlug }}">
                                        {{ ucfirst($incidente->gravedad) }}
                                    </span>
                                </td>
                                <td id="perfil-incidente-ubicacion-{{ $incidente->id }}">{{ $incidente->ubicacion }}</td>
                                <td id="perfil-incidente-estado-{{ $incidente->id }}">
                                    <span class="perfil-badge perfil-badge-neutro perfil-estado-{{ $estadoSlug }}">
                                        {{ ucfirst($incidente->estado) }}
                                    </span>
                                </td>
                                <td id="perfil-incidente-accion-{{ $incidente->id }}">
                                    <a
                                        id="perfil-ver-detalle-{{ $incidente->id }}"
                                        href="{{ route('incidentes.show', $incidente->id) }}"
                                        class="perfil-btn-detalle"
                                    >
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection