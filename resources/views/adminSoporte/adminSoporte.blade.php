@extends('welcome')
@section('contenido')
<div class="clearfix"></div>
@include('solicitudSoporte.botones')
<div id="listadoTickets">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2 id="titleListadoTickets"> </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                            class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings 1</a></li>
                                <li><a href="#">Settings 2</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="close-link"><i class="fa fa-close"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="TableTickets" class="table table-striped table-bordered table-hover table-condensed" style="font-size:12px;" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th style="text-align: center;"><i class="ace-icon fa fa-paperclip"></i></th>
                                <th>Solicitante</th>
                                <th>Ejecutor</th>
                                <th>Asunto</th>
                                <th>Categoría</th>
                                <th>Estado</th>
                                <th>Ultima Actualización</th>
                                <th>Prio.</th>
                                <th>Ver</th>
                            </tr>
                        </thead>
                    <tbody id="listaListadoTicketDetalleOK"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@include('solicitudSoporte.modal-detalle-ticket')
@include('solicitudSoporte.modal-anular-ticket')
@include('solicitudSoporte.modal-archivos-adjuntos')
@include('solicitudSoporte.modal-detalle-imagen')
@include('adminSoporte.modal-pausar-ticket')
@include('adminSoporte.modal-nuevo-equipo')
@include('adminSoporte.modal-tipo-solucion')
@include('adminSoporte.modal-terminar-ticket')
@include('adminSoporte.modal-asignar-agente')
@include('adminSoporte.modal-adjunto-resultado')
@include('adminSoporte.modal-cambiar-area')
@include('adminSoporte.modal-reapertura-ticket')
@include('adminSoporte.modal-chat')

@endsection
@section('javascript')
<script src="{{ asset('jsApp/adminSoporte.js')}}"></script>
<link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
<script src="{{ asset('js/sweetalert2.all.min.js')}}"></script>
<script src="{{ asset('js/jquery.elevatezoom.js')}}"></script>
@stop