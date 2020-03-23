@extends('welcome')
@section('contenido')
<div class="title_left row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <h3><i class="far fa-building"></i> Maestro de Empresas.</h3>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <button id="btnAgregarEmpresa"  style="float: right;" type="button" class="btn btn-round btn-success"><i class="fas fa-plus"></i> Agregar nueva Empresa</button>
    </div> 
</div>

<br>
<div id="divTableUsuarios" class="x_content">
    <table id="datatable-empresas" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Ruc</th>
                <th>Base de Datos</th>
                <th>Usuarios</th>
                <th>User Admin</th>
                <th>Tickets</th>
                <th>Status</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody id="body-empresas">
            
        </tbody>
    </table>
</div>
@include('mantenimiento.modal-maestro-empresa')
@endsection
@section('javascript')
<script src="{{ asset('jsApp/maestroEmpresas.js')}}"></script>
@stop