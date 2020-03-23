@extends('welcome')
@section('contenido')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					Usuarios
				</div>
				<div class="card-body">
					<table id="datatable-empresas" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th width="10px">Id</th>
								<th>Nombre</th>
								<th colspan="3"></th>
								
							</tr>
						</thead>
						<tbody>
							@foreach($users as $user)
							<tr>
								<td>{{ $user->id }}</td>
								<td>{{ $user->name }}</td>
								<td style="width: 10px;">
									@can('users.show')
										<a class="btn btn-sm btn-primary" href="{{ route('users.show',$user->id) }}">
											Ver
										</a>
									@endcan
								</td>
								<td style="width: 10px;">
									@can('users.edit')
										<a class="btn btn-sm btn-warning" href="{{ route('users.edit',$user->id) }}">
											Editar
										</a>
									@endcan
								</td>
								<td style="width: 10px;">
									@can('users.destroy')
										{!! Form::open(['route' => ['users.destroy',$user->id],'method'=>'DELETE']) !!}
											<button class="btn btn-danger">
												Eliminar
											</button>
										{!! Form::close() !!}
											
									
										</a>
									@endcan
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					{{ $users->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection