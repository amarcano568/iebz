@extends('welcome')
@section('contenido')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<main class="py-4">
				@if(session('info'))
				<div class="container">
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<div class="alert alert-success fade show alert-dismissible ">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
								{{ session('info') }}
							</div>
						</div>
					</div>
				</div>
				@endif
			</main>
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-lg-8">
							<h4><i class="fab fa-r-project"></i> Mantenimiento de Roles</h4>
						</div>
						<div class="col-lg-4">
							@can('roles.create')
							<a href="{{ route('roles.create') }}" class="btn btn-primary" style="float: right;">
								Crear
							</a>
							@endcan
						</div>
					</div>
				</div>
				<div class="card-body">
					<table id="datatable-empresas" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr style="text-align: center;">
								<th width="10%">Id</th>
								<th width="50%">Nombre del Role</th>
								<th width="20%" colspan="3">Opciones</th>
							</tr>
						</thead>
						<tbody id="body-roles">
							@foreach($roles as $role)
							<tr>
								<td>{{ $role->id }}</td>
								<td>{{ $role->name }}</td>
								
								@can('roles.show')
								<td width="10px">
									<a idRole="{{ $role->id }}" data-accion="ver-role" href="{{ route('roles.show', $role->id) }}"
										class="btn btn-sm btn-info">
										<i class="fab fa-searchengin"></i>
									</a>
								</td>
								@endcan
								@can('roles.edit')
								<td width="10px">
									<a urlRole="/roles/{{ $role->id }}/edit" idRole="{{ $role->id }}" data-accion="editar-role" href="{{ route('roles.edit', $role->id) }}"
										class="btn btn-sm btn-success">
										<i class="far fa-edit"></i>
									</a>
								</td>
								@endcan
								@can('roles.destroy')
								<td width="10px">
									{!! Form::open(['route' => ['roles.destroy', $role->id],
									'method' => 'DELETE']) !!}
									<button class="btn btn-sm btn-danger">
									<i class="far fa-trash-alt"></i>
									</button>
									{!! Form::close() !!}
								</td>
								@endcan
							</tr>
							@endforeach
						</tbody>
					</table>
					{{ $roles->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@include('roles.modal-ver-role')
@endsection
@section('javascript')
<script src="{{ asset('jsApp/roles.js')}}"></script>
@stop