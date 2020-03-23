@extends('welcome')
@section('contenido')
<div class="title_left">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <h3><i class="fas fa-birthday-cake"></i> Reporte de Cumpleaños.</h3>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6" style="float: right;">
            <select class="chosen-select" name="mes" id="mes">
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="float: right;">
            <select class="chosen-select" name="iglesia" id="iglesia">
                <option value="1">IEBZ => Zaragoza</option>
                <option value="2">IEBT => Tudela</option>
            </select>
        </div>
    </div>
    
</div>
<div id="divTableUsuarios" class="x_content">
    <br><br>
    <center id="listado-de-cumpleanos-mes">
    <img src="img/feliz-cumpleanos.png" alt="">
    </center>
</div>
@endsection
@section('javascript')
<script src="{{ asset('jsApp/cumpleanos.js')}}"></script>
@stop