@extends('welcome')
@section('contenido')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Usuario</div>

                <div class="card-body">
                    {!! Form::model($user,['route' => ['users.update',$user->id],'method' => 'PUT']) !!}
						@include('users.partial.form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('jsApp/funcGral.js')}}"></script>
@stop