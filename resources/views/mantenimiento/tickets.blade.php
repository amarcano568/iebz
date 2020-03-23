@extends('welcome')
@section('contenido')

    <div class="row">   
        <h3 class="title_left">
            <i class="fas fa-users"></i> Mantenimiento de Tipos de Tickets.
        </h3>
    </div>
    <button type="button" class="btn btn-round btn-success float-right" id="BtnNuevo" formnovalidate="" style="margin-top: -2em;">
            <i class="ace-icon fa fa-file-o blue"></i>
            Nuevo Tipo
    </button>

<br>

<div id="divTableUsuarios" class="x_content">
    <table id="tablePrincipal" class="table table-bordered table-hover table-condensed" style="width: 100%;">
        <thead>
            <tr>
                <th style="text-align: center;"></th>
                <th style="text-align: center;">Tipo</th>
                <th style="text-align: center;">Id</th>
                <th style="text-align: center;">Descripción</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Detalle</th>
                <th style="text-align: center;">Opciones</th>
            </tr>
        </thead>
        <tbody id="bodyTablePrincipal">
        </tbody>
    </table>
</div>

@include('mantenimiento.modal-ticket')
@endsection
@section('javascript')
<script src="{{ asset('jsApp/mantTipoTicket.js')}}"></script>
@stop