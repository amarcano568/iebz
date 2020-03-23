@extends('welcome')
@section('contenido')
<div class="title_left">
    <h3><i class="fas fa-envelope-open-text"></i> Configuración de correos.</h3>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Configuración</h2>
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

<form id="form_actualizar_correo" method="post" enctype="multipart/form-data" action="actualiza-correo">
    @csrf
    <div class="item form-group">
        <label for="middle-name" class="col-form-label col-lg-3 col-md-3 col-sm-3 label-align">Nombre</label>
        <div class="col-lg-3 col-md-6 col-sm-6 ">
            <input id="nombreEmail" class="form-control" type="text" name="nombreEmail" value="{{{ isset($correo->nombre) ? $correo->nombre : '' }}}">
        </div>
    </div>
    <div class="item form-group">
        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">smtp</label>
        <div class="col-md-6 col-sm-6 ">
            <input id="smtpEmail" class="form-control" type="text" name="smtpEmail" value="{{{ isset($correo->smtp) ? $correo->smtp : '' }}}">
        </div>
    </div>
    <div class="item form-group">
        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Port</label>
        <div class="col-md-6 col-sm-6 ">
            <input id="portEmail" class="form-control" type="text" name="portEmail" value="{{{ isset($correo->port) ? $correo->port : '' }}}">
        </div>
    </div>
    <div class="item form-group">
        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">encryption</label>
        <div class="col-md-6 col-sm-6 ">
            <input id="encryptionEmail" class="form-control" type="text" name="encryptionEmail" value="{{{ isset($correo->encryption) ? $correo->encryption : '' }}}">
        </div>
    </div>
    <div class="item form-group">
        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Correo</label>
        <div class="col-md-6 col-sm-6 ">
            <input id="correoEmail" class="form-control" type="text" name="correoEmail" value="{{{ isset($correo->correo) ? $correo->correo : '' }}}">
        </div>
    </div>
    <div class="item form-group">
        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Password</label>
        <div class="col-md-6 col-sm-6 ">
            <input id="passwordEmail" class="form-control" type="text" name="passwordEmail" value="{{{ isset($correo->password) ? $correo->password : '' }}}">
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