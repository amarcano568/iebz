@extends('welcome')
@section('contenido')
<div class="title_left">
    <h3><i class="fas fa-users"></i> Mantenimiento de Miembros.</h3>
</div>

<div id="divTableUsuarios" class="x_content">
    <button type="button" id="btnAgregarMiembro" class="btn btn-round btn-success" style="float: right;">Agregar nuevo Miembro</button>
<table id="tableMiembros" class="table table-striped table-bordered" style="width:100%">
<thead>
    <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Apellido 1</th>
        <th>Apellido 2</th>
        <th>Genero</th>
        <th></th>
    </tr>
</thead>
<tbody id="body-miembros">
    
</tbody>
</table>
</div>


@endsection

@section('javascript')
    <script src="{{ asset('jsApp/relacion-generos.js')}}"></script> 
@stop