@extends('welcome')
@section('contenido')
<div class="row">
    <h3 class="title_left">
    <i class="fas fa-users"></i> Mantenimiento de Áreas y Subareas.
    </h3>
</div>
<button type="button" class="btn btn-round btn-success float-right" id="BtnAgregarArea" formnovalidate="" style="margin-top: -2em;">
<i class="ace-icon fa fa-file-o blue"></i>
Nuevo
</button>
<br>
<div id="divTableCategorias" class="x_content table-responsive">
    <table id="TableListadoArea" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
        <thead>
            <tr>
                <th style="text-align: center;"></th>
                <th style="text-align: center;width: 10%">Id</th>
                <th style="text-align: center;width: 10%">Nombre del área</th>
                <th style="text-align: center;width: 50%">Status</th>
                <th style="text-align: center;">subAreas</th>
                <th style="text-align: center;width: 20%">Opciones</th>
            </tr>
        </thead>
        <tbody id="listaAreaOK">
            
        </tbody>
    </table>
</div>

@include('mantenimiento.modal-area')
@include('mantenimiento.modal-subarea')
@endsection
@section('javascript')
<script src="{{ asset('jsApp/Areas.js')}}"></script>
@stop