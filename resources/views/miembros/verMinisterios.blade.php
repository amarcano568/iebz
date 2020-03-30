@extends('welcome')
@section('contenido')
<div class="title_left">
  <h3><i class="fas fa-project-diagram"></i> Ministerios.</h3>
</div>
<ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
  <li class="nav-item" id="tab-home">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Organigrama de Ministerios </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-users"></i> Mantenimiento de Ministerios</a>
  </li>
  
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab" style="width: 100%">
    @include('miembros.organigrama-ministerios')
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    @include('miembros.mantenimiento-ministerios')
  </div>
  
</div>
@include('miembros.modal-miembro-ministerio')
@include('miembros.modal-ministerio')
@endsection
@section('javascript')
<link href="{{ asset('css/organigrama.css') }}" rel="stylesheet">
<script src="{{ asset('jsApp/ministerios.js')}}"></script>
<script src="{{ asset('js/printThis.js')}}"></script>
@stop