@extends('welcome')
@section('contenido')
<div class="title_left">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
            <h3><i class="fas fa-birthday-cake"></i> Miembros por Rango de Edades.</h3>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6" style="float: right;">
            <select class="chosen-select" id="idIglesia" name="idIglesia" data-placeholder="Seleccione una Iglesia..." required="">
                @foreach( $iglesias as $iglesia )
                <option value="{{$iglesia->id}}">
                    {{$iglesia->nombreCorto}}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6" style="float: right;">
            <select class="chosen-select" id="status" name="status" data-placeholder="Seleccione un status...">
                @foreach( $status as $statu )
                <option value="{{$statu->id}}">
                    {{$statu->nombre}}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6  ">
            <input type="text" id="rangeEdad" value="" name="rangeEdad" />
        </div>
    </div>
    
</div>
<div id="divTableUsuarios" class="x_content">
    <br><br>
    <center id="listado-de-rango-edad">
    <img src="img/feliz-cumpleanos.png" alt="">
    </center>
</div>
@endsection
@section('javascript')
<script src="{{ asset('jsApp/edades.js')}}"></script>
@stop