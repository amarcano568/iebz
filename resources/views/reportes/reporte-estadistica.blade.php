@extends('welcome')
@section('contenido')

<h4 style="text-align: center" class="text-info">Reporte en Desarrollo</h4>

@include('reportes.modal-graficos')
@endsection
@section('javascript')
<script src="{{ asset('vendors/raphael/raphael.min.js')}}"></script>
<script src="{{ asset('vendors/morris.js/morris.min.js')}}"></script>
<script src="{{ asset('jsApp/reportes.js')}}"></script>
@stop