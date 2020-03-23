@extends('welcome')
@section('contenido')
<div class="title_left">
    <h3><i class="far fa-user-circle"></i> Perfil del Usuario.</h3>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <div class="">
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 ">
                            <div class="x_panel">
                                
                                <div class="x_content">
                                    <div class="col-md-3 col-sm-3  profile_left">
                                        <div class="profile_img">
                                            <div id="crop-avatar" style="text-align: center;">
                                                {{-- <img class="img-responsive avatar-view" src="images/picture.jpg" alt="Avatar" title="Change the avatar"> --}}
                                                <div  id="formDropZone"></div>
                                            </div>
                                        </div>
                                        <h5>{{$usuario->name }} {{$usuario->lastName }}</h5>
                                        <ul class="list-unstyled user_data">
                                            <li>
                                                <i class="fa fa-briefcase user-profile-icon"></i> {{$usuario->nomCargo }}
                                            </li>
                                            <li class="m-top-xs">
                                                <i class="fas fa-medal"></i>
                                            </li>
                                        </ul>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            </div>
        </div>
    </div>
</div>
<br>
@endsection
@section('javascript')
<script src="{{ asset('vendors/raphael/raphael.min.js')}}"></script>
<script src="{{ asset('vendors/morris.js/morris.min.js')}}"></script>
<script src="{{ asset('jsApp/perfil.js')}}"></script>
@stop