@extends('welcome')
@section('contenido')
<div class="row">
    <h3 class="title_left">
    <i class="fas fa-users"></i> Mantenimiento de Categorías y Subcategorías.
    </h3>
</div>
<button type="button" class="btn btn-round btn-success float-right" id="BtnAgregarCategoria" formnovalidate="" style="margin-top: -2em;">
<i class="ace-icon fa fa-file-o blue"></i>
Nuevo
</button>
<br>
<div id="divTableCategorias" class="x_content table-responsive">
    <table id="tableCategorias" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
        <thead>
            <tr>
                <th style="text-align: center;"></th>
                <th style="text-align: center;">Id</th>
                <th style="text-align: center;width: 20%">Nombre_Categoría</th>
                <th style="text-align: center;">Área</th>
                <th style="text-align: center;">Tipo</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">detalle</th>
                <th style="text-align: center;">Aplicacion</th>
                <th style="text-align: center;">Opciones</th>
            </tr>

        </thead>
        
        <tbody id="bodyCategorias">
            
        </tbody>
    </table>
</div>
@include('mantenimiento.modal-ticket')
@include('mantenimiento.modal-subcategoria')
@include('mantenimiento.modal-aplicacion')
@include('mantenimiento.modal-categoria')
@endsection
@section('javascript')
<script src="{{ asset('jsApp/Categoria.js')}}"></script>
@stop