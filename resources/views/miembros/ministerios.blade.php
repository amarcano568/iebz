@extends('welcome')
@section('contenido')
<div class="title_left">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
            <h3><i class="fas fa-project-diagram"></i> Informe de Ministerios.</h3>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6" style="float: right;">
            <select class="chosen-select" id="idMinisterio" name="idMinisterio" data-placeholder="Seleccione un Ministerio..." required="">
                <option value="*">Todos los ministerios</option>
                @foreach( $ministerios as $ministerio )
                <option value="{{$ministerio->id}}">
                    {{$ministerio->nombre}}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    
</div>
<div id="divTableUsuarios" class="x_content">
    <br><br>
    <center id="listado-de-ministerios">
    <i class="fa-8x fas fa-project-diagram"></i>
    </center>
</div>
@endsection
@section('javascript')
<script src="{{ asset('jsApp/informe-ministerios.js')}}"></script>
@stop