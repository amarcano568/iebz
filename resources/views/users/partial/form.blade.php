<div class="form-group">
	{{ Form::label('name', 'Nombre del Usuario') }}
	{{ Form::text('name' , null, ['class' => 'form-control', 'id' => 'name', 'readonly'=>'']) }}
</div>
<hr>
<h3>Lista de roles</h3>
<div class="form-group">
	<ul class="list-unstyled">
		@foreach($roles as $role)
	    <li>
	        <label>
	        {{ Form::checkbox('roles[]', $role->id, null) }}
	        {{ $role->name }}
	        <em>({{ $role->description }})</em>
	        </label>
	    </li>
	    @endforeach
    </ul>
</div>
<div class="form-group">
	{{ Form::submit('Guardar', ['class' => 'btn btn-sm btn-primary']) }}
	{{ Form::button('<i class="fas fa-arrow-left"></i> Regresar', ['type' => 'button', 'class' => 'btn btn-warning btn-sm', 'id'=>'btnRetornar'] )  }}
</div>