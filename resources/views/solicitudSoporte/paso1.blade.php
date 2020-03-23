<form id="FormCanalTicket" method="post" enctype="multipart/form-data">
	@csrf
	<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-6">
		<label>Área de Atención</label>
		<select data-placeholder="Seleccione una Categoría..." class="form-control chosen-select" id="Area" name="Area" required style="width: 100%" required="">
			@foreach( $areas as $area )
			<option value="{{$area->idArea}}">
				{{$area->descArea}}
			</option>
			@endforeach
		</select>
	</div>
	<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-6">
		<label>Tipo</label>
		<select data-placeholder="Seleccione el Tipo de Ticket..." class="form-control chosen-select" id="TipoTicket" name="TipoTicket" required style="width: 100%" required="">
		</select>
		
	</div>
	<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-6">
		<label>Categoría</label>
		<select data-placeholder="Seleccione la categoría del Ticket..." class="form-control chosen-select" id="CategoriaX" name="CategoriaX" required style="width: 100%" required="">
		</select>
	</div>
	<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-6">
		<label>SubCategoría</label>
		<select data-placeholder="Seleccione la categoría del Ticket..." class="form-control chosen-select" id="SubCategoria" name="SubCategoria" style="width: 100%">
		</select>
	</div>
	<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-6">
		<label >Aplicación</label>
		<select data-placeholder="Seleccione la Aplicación..." class="form-control chosen-select" id="Problemas" name="Problemas"  style="width: 100%">
		</select>
	</div>
	<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-6">
		<label>Prioridad</label>
		<select data-placeholder="Seleccione prioridad..." class="form-control chosen-select" id="TipoPrioridad" name="TipoPrioridad" required style="width: 100%">
			@foreach( $prioridades as $prioridad )
			<option value="{{$prioridad->idTabla}}">
				{{$prioridad->desTabla}}
			</option>
			@endforeach
		</select>
	</div>
	{{-- <div class="form-group col-lg-6 col-md-6 col-sm-6" style="">
		<label class="control-label no-padding-right" for="form-field-1"> Usuario </label>
		<div class="clearfix">
			<select data-placeholder="Seleccione un Usuario..." class="chosen-select form-control" id="Referencia" name="Referencia" >
				<option value="">
				</option>
				@foreach( $personal as $persona )
				<option value="{{$persona->nomPersonal}}">
					{{$persona->nomPersonal}}
				</option>
				@endforeach
			</select>
		</div>
	</div> --}}
	<div class="form-group col-lg-6 col-md-6 col-sm-6" style="">
		<label class="control-label no-padding-right" for="form-field-1"> Con Copia a </label>
		<select multiple data-placeholder="Correo con copia a..." class="form-control chosen-select" id="ConCopia" name="ConCopia" style="width: 100%">
			@foreach( $usuarios as $usuario )
			<option value="{{$usuario->id}}">
				{{$usuario->name}} {{$usuario->lastName}}
			</option>
			@endforeach
		</select>
	</div>
</form>