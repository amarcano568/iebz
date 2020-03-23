@extends('welcome')
@section('contenido')

    <div class="row">   
        <h3 class="title_left">
            <i class="fas fa-users"></i> Mantenimiento de Profesiones.
        </h3>
    </div>
    <button type="button" class="btn btn-round btn-success float-right" id="BtnNuevo" formnovalidate="" style="margin-top: -2em;">
            <i class="ace-icon fa fa-file-o blue"></i>
            Nuevo
    </button>

<br>

<div id="" class="">
    <table id="tableProfesiones" class="table table-bordered table-hover table-condensed" style="width: 100%;">
        <thead>
            <tr>
                
                
                <th style="text-align: center;">Id</th>
                <th style="text-align: center;">Descripción</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Opciones</th>
            </tr>
        </thead>
        <tbody id="bodyTableProfesiones">
        </tbody>
    </table>
</div>

@include('mantenimiento.modal-profesion')
@endsection
@section('javascript')
<script src="{{ asset('jsApp/mantProfesiones.js')}}"></script>
@stop