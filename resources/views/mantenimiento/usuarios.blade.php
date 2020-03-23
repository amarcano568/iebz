@extends('welcome')
@section('contenido')
<div class="title_left">
    <h3><i class="fas fa-users"></i> Mantenimiento de Usuarios.</h3>
</div>
<div class="form-group row">
    <div class="col-lg-12 col-md-12">
        <div class="x_panel">
            <div class="x_title">
                
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#"></a>
                        <a class="dropdown-item" href="#"></a>
                    </div>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">        

        <div class="col-lg-12 col-md-3 col-sm-3">
            <button id="btnAgregarUsuario"  style="float: right;" type="button" class="btn btn-round btn-success"><i class="fas fa-user-plus"></i> Agregar nuevo Usuario</button>
        </div>     
    </div>
</div>
</div>

</div>
<br>
<div id="divTableUsuarios" class="x_content">
<table id="datatable-usuarios" class="table table-striped table-bordered" style="width:100%">
<thead>
    <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Correo</th>
        <th>Rol</th>
        <th>Status</th>
        <th>Opciones</th>
    </tr>
</thead>
<tbody id="body-usuarios">
    
</tbody>
</table>
</div>

@include('mantenimiento.modal-usuarios')
@endsection

@section('javascript')
<script src="{{ asset('jsApp/usuarios.js')}}"></script>
@stop