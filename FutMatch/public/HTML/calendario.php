<?php
// Componente de Calendario Reutilizable
// Extrae la funcionalidad base del calendario desde agenda_AdminCancha.php
// Sin elementos específicos del admin, solo la funcionalidad esencial
?>

<!-- Componente Calendario -->
<div class="calendario-component">
    <!-- Header de controles del calendario -->
    <div class="row mb-4 align-items-center calendario-header">
        <div class="col-md-4">
            <select class="form-select" id="selectorCancha">
                <option selected>Seleccionar cancha</option>
                <option value="1">Cancha A - Fútbol 11</option>
                <option value="2">Cancha B - Fútbol 7</option>
                <option value="3">Cancha C - Fútbol 5</option>
            </select>
            <label class="form-label small text-muted" for="selectorCancha">Filtrar por cancha</label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" id="selectorFecha">
            <label class="form-label small text-muted" for="selectorFecha">Ir a fecha</label>
        </div>
        <div class="col-md-4 text-end">
            <button id="botonHoy" class="btn btn-dark me-2" type="button">
                <i class="bi bi-calendar-today"></i> Hoy
            </button>
            <div class="btn-group me-2" role="group" aria-label="Navigate calendar">
                <button type="button" class="btn btn-dark" id="botonAnterior">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-dark" id="botonSiguiente">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
            <div class="dropdown d-inline-block">
                <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownVista" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="vistaActual">Mes</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownVista">
                    <li><a class="dropdown-item selector-vista" href="#" data-vista="mes">Mes</a></li>
                    <li><a class="dropdown-item selector-vista" href="#" data-vista="semana">Semana</a></li>
                    <li><a class="dropdown-item selector-vista" href="#" data-vista="dia">Día</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Área principal del calendario -->
    <div class="calendario-main-container">
        <div class="row h-100">
            <div class="col-12 d-flex flex-column">

                <!-- Contenido del calendario -->
                <div id="contenidoCalendario" class="d-flex flex-column flex-grow-1">
                    <!-- Vista mensual -->
                    <div id="vistaMensual" class="vista-calendario d-flex flex-column">
                        <div class="table-responsive flex-grow-1">
                            <table id="calendario-mes" class="table table-bordered h-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">Dom</th>
                                        <th scope="col">Lun</th>
                                        <th scope="col">Mar</th>
                                        <th scope="col">Mié</th>
                                        <th scope="col">Jue</th>
                                        <th scope="col">Vie</th>
                                        <th scope="col">Sáb</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Se llena dinámicamente con JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Vista semanal -->
                    <div id="vistaSemanal" class="vista-calendario d-none">
                        <div class="table-responsive">
                            <table id="calendario-semana" class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" width="100">Hora</th>
                                        <th scope="col">Dom</th>
                                        <th scope="col">Lun</th>
                                        <th scope="col">Mar</th>
                                        <th scope="col">Mié</th>
                                        <th scope="col">Jue</th>
                                        <th scope="col">Vie</th>
                                        <th scope="col">Sáb</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Se llena dinámicamente con JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Vista diaria -->
                    <div id="vistaDiaria" class="vista-calendario d-none">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table id="calendario-dia" class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col" width="100">Hora</th>
                                                <th scope="col" id="encabezadoVistaDiaria">Día</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Se llena dinámicamente con JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="bi bi-calendar-day"></i> Resumen del día</h6>
                                    </div>
                                    <div class="card-body" id="resumenDia">
                                        <!-- Se llena dinámicamente con JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Los estilos CSS del calendario ahora están en agenda.css -->

<!-- El JavaScript del calendario ahora se maneja desde archivos externos modulares -->