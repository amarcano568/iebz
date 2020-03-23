@extends('welcome')
@section('contenido')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Role</div>

                <div class="card-body">
                    {!! Form::model($role,['route' => ['roles.update',$role->id],'method' => 'PUT']) !!}
						@include('roles.partial.form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('jsApp/roles.js')}}"></script>
@stop