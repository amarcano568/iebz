@extends('welcome')
@section('contenido')
<div class="title_left">
    <h3><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><i class="fas fa-users"></i> Mantenimiento de Empresa.</font></font></h3>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Empresa <small>información</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="dropdown-item" href="#">Guardar</a>
                    </li>
                    <li><a class="dropdown-item" href="#">Cancelar</a>
                </li>
            </ul>
        </li>
        <li><a class="close-link"><i class="fa fa-close"></i></a>
    </li>
</ul>
<div class="clearfix"></div>
</div>
<div class="x_content">
<br />
<form id="form_actualizar_empresa" method="post" enctype="multipart/form-data" action="actualiza-empresa">
  @csrf
    <div class="item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nombre <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 ">
        <input type="text" id="empresa" name="empresa" required="required" class="form-control" value="{{$empresa->NombreEmpresa }}">
    </div>
</div>
<div class="item form-group">
    <label class="col-form-label col-lg-3 col-md-3 col-sm-3 label-align" for="last-name">Ruc <span class="required">*</span>
</label>
<div class=" col-lg-3 col-md-6 col-sm-6 ">
    <input type="text" id="ruc" name="ruc" required="required" class="form-control" value="{{ $empresa->ruc }}">
</div>
</div>
<div class="item form-group">
<label for="middle-name" class="col-form-label col-lg-3 col-md-3 col-sm-3 label-align">Teléfono 1</label>
<div class="col-lg-3 col-md-6 col-sm-6 ">
    <input id="telefono1" class="form-control" type="text" name="telefono1" value="{{$empresa->telefono1}}">
</div>
</div>
<div class="item form-group">
<label for="middle-name" class="col-form-label col-lg-3 col-md-3 col-sm-3 label-align">Teléfono 2</label>
<div class="col-lg-3 col-md-6 col-sm-6 ">
    <input id="telefono2" class="form-control" type="text" name="telefono2" value="{{$empresa->telefono2}}">
</div>
</div>
<div class="item form-group">
<label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Dirección</label>
<div class="col-md-6 col-sm-6 ">
    <input id="direccion" class="form-control" type="text" name="direccion" value="{{$empresa->direccion}}">
</div>
</div>
<div class="item form-group">
<label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Correo</label>
<div class="col-md-6 col-sm-6 ">
    <input id="correo" class="form-control" type="email" name="correo" value="{{$empresa->correo}}">
</div>
</div>
<div class="item form-group">
<label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Representante</label>
<div class="col-md-6 col-sm-6 ">
    <input id="representante" class="form-control" type="text" name="representante" value="{{$empresa->representante}}">
</div>
</div>
<div class="item form-group">
<label for="middle-name" class="col-form-label col-lg-3 col-md-3 col-sm-3 label-align">Usuarios permitidos</label>
<div class="col-lg-1 col-md-6 col-sm-6 ">
    <input id="usuarios" class="form-control" type="text" name="usuarios" readonly value="{{$empresa->usuariosPermitidos}}">
</div>
</div>

<div class="ln_solid"></div>
<div class="item form-group">
<div class="col-md-6 col-sm-6 offset-md-3">
    <button id="btnAgregarUsuario"  style="float: center;" type="submit" class="btn btn-round btn-success"><i class="fas fa-user-plus"></i> Guardar</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
<br>
@endsection
@section('javascript')
<script src="{{ asset('jsApp/empresa.js')}}"></script>
@stop