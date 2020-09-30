@extends('welcome')
@section('contenido')
<div class="title_left">
    <h3><i class="fas fa-users"></i> Mantenimiento de Miembros.</h3>
</div>

<div id="divTableUsuarios" class="x_content">
    <div class="row">
        <div class="col-sm-4">
            <label for="" class="float-right">Filtro</label>
        </div>
        <div class="col-sm-4">
                <select class="chosen-select" id="filtroNew" name="filtroNew" data-placeholder="Seleccione un status...">
                    <option value="0,1,2,3,4">Todos los miembros</option>
                    @foreach( $status as $statu )
                    <option value="{{$statu->id}}">
                        {{$statu->nombre}}
                    </option>
                    @endforeach
                </select>
        </div>
        <div class="col-sm-4">
            <button type="button" id="btnAgregarMiembro" class="btn btn-round btn-success" style="float: right;">Agregar nuevo Miembro</button>
        </div>
    </div>
    
<table id="tableMiembros" class="table table-striped table-bordered" style="width:100%">
<thead>
    <tr>
        <th></th>
        <th>Id</th>
        <th>Iglesia</th>
        <th>Nombre</th>
        <th>Apellido 1</th>
        <th>Apellido 2</th>
        <th>Edad</th>
        <th><i class="fas fa-mobile-alt"></i></i> Movil</th>
        <th>Status</th>
        <th>Detalle</th>
        <th></th>
    </tr>
</thead>
<tbody id="body-miembros">
    
</tbody>
</table>
</div>
@include('miembros.modal-miembro')
@include('miembros.modal-pdf')
@include('miembros.modal-foto')
@include('miembros.modal-agregar-documento')
@include('miembros.modal-detalle-imagen')
@include('miembros.modal-agregar-miembro-existente')
@include('mantenimiento.modal-profesion-miembros')

@endsection

@section('javascript')
    <script src="{{ asset('jsApp/miembros.js')}}"></script> 
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('js/jquery.elevatezoom.js')}}"></script>
@stop