@extends('welcome')
@section('contenido')
<!-- top tiles -->
<div class="row" style="display: inline-block;" >
  <div class="tile_count" id="tableroInfo">
    
  </div>
</div>
<!-- /top tiles -->
<div class="row">
  <div class="col-md-6 col-sm-6  ">
    <div class="x_panel">
      <div class="x_title">
        <h2><small>Status de</small> Miembros <i class="fas fa-users"></i><small></small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="#">Settings 1</a>
            <a class="dropdown-item" href="#">Settings 2</a>
          </div>
        </li>
        <li><a class="close-link"><i class="fa fa-close"></i></a>
      </li>
    </ul>
    <div class="clearfix"></div>
  </div>
  <div class="x_content">
    <canvas id="pieChartMiembros" height="168" width="338" style="width: 271px; height: 135px;"></canvas>
  </div>
</div>
</div>
<div class="col-md-6 col-sm-6  ">
<div class="x_panel">
  <div class="x_title">
    <h2><small>Distribución de</small> Género <i class="fas fa-venus-mars"></i><small></small></h2>
    <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
    </li>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#">Settings 1</a>
        <a class="dropdown-item" href="#">Settings 2</a>
      </div>
    </li>
    <li><a class="close-link"><i class="fa fa-close"></i></a>
  </li>
</ul>
<div class="clearfix"></div>
</div>
<div class="x_content">
<canvas id="graficoGenero" height="168" width="338" style="width: 271px; height: 135px;"></canvas>
</div>
</div>
</div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-8 col-sm-8  ">
<div class="x_panel">
<div class="x_title">
<h2>Mapa del mundo</h2>
<ul class="nav navbar-right panel_toolbox">
<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
</li>
<li class="dropdown">
 <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
<a class="dropdown-item" href="#">Settings 1</a>
<a class="dropdown-item" href="#">Settings 2</a>
</div>
</li>
<li><a class="close-link"><i class="fa fa-close"></i></a>
</li>
</ul>
<div class="clearfix"></div>
</div>
<div class="x_content">
<div id="echart_world_map" style="height: 370px; -webkit-tap-highlight-color: transparent; user-select: none; position: relative; background-color: transparent;" _echarts_instance_="ec_1585235080298"><div style="position: relative; overflow: hidden; width: 784px; height: 370px; cursor: default;"><canvas width="980" height="462" data-zr-dom-id="zr_0" style="position: absolute; left: 0px; top: 0px; width: 784px; height: 370px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></canvas></div><div style="position: absolute; display: none; border-style: solid; white-space: nowrap; z-index: 9999999; transition: left 0.4s cubic-bezier(0.23, 1, 0.32, 1) 0s, top 0.4s cubic-bezier(0.23, 1, 0.32, 1) 0s; background-color: rgba(0, 0, 0, 0.5); border-width: 0px; border-color: rgb(51, 51, 51); border-radius: 4px; color: rgb(255, 255, 255); font: 14px / 21px Arial, Verdana, sans-serif; padding: 5px; left: 176.938px; top: 100.875px;">Población mundial (2010) <br>Estados Unidos de América: 312,247.116</div></div>
</div>
</div>
</div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('jsApp/inicio.js')}}"></script>
@stop