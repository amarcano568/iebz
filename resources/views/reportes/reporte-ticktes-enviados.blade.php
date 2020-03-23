@extends('welcome')
@section('contenido')
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label>Usuarios</label>
            <select data-placeholder="Seleccione una Categoría..." class="form-control chosen-select" id="usuarios" name="usuarios" required style="width: 100%">
                <option value="0">Todos los usuarios</option>
                @foreach( $usuarios as $usuario )
                <option value="{{$usuario->id}}">
                    {{$usuario->name}} {{$usuario->lastName}}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label>Estados</label>
            <select data-placeholder="Seleccione una Categoría..." class="form-control chosen-select" id="grupo" name="grupo" required style="width: 100%">
                <option value="0">Todos los estados</option>
                <option value="1">Pendiente</option>
                <option value="2">Asignado</option>
                <option value="3">Anulado</option>
                <option value="4">Reasignado</option>
                <option value="5">Iniciado</option>
                <option value="6">Ticket Reaperturado</option>
                <option value="7">Pausado</option>
                <option value="8">Resuelto</option>
                <option value="9">Cerrado</option>
            </select>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <label>Período</label>
        <div class="form-group">
            <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                <i class="far fa-calendar-alt"></i>
                <span></span> <i class="fa fa-caret-down"></i>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table id="TableTickets" class="table table-striped table-bordered table-hover table-condensed" style="font-size:12px;" width="100%">
            <thead>
                <tr>
                    <th>Grupo</th>
                    <th>Ticket</th>
                    <th>Solicitante</th>
                    <th>Ejecutor</th>
                    <th>Asunto</th>
                    <th>Categoría</th>
                    <th>Prio.</th>
                </tr>
            </thead>
        <tbody id="listaListadoTicketDetalleOK"></tbody>
    </table>
</div>
</div>
@include('reportes.modal-graficos')
@endsection
@section('javascript')
<script src="{{ asset('vendors/raphael/raphael.min.js')}}"></script>
<script src="{{ asset('vendors/morris.js/morris.min.js')}}"></script>
<script src="{{ asset('jsApp/reportes.js')}}"></script>
@stop