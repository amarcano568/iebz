<div id="ModalAsignarTicket" class="modal fade" data-backdrop="static" data-keyboard="false" >
	<div class="modal-dialog modal-md  modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header modal-header-info">				
				<h6 class="modal-title" ><i class="fa fa-check"></i> Asignar Ticket a un Agente de Atención</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body" >
				<h6 class="modal-title" >Elija un Agente de Atención</h6>
				<select data-placeholder="Seleccione una Categoría..." class="form-control chosen-select" id="Agente" name="Agente" required style="width: 100%" required="">
					@foreach( $agentes as $agente )
					<option value="{{$agente->id}}">
						{{$agente->name}} {{$agente->lastName}} - {{$agente->email}}
					</option>
					@endforeach
				</select>
				
			</div>
			<div class="modal-footer modal-footer-danger">
				<button class="btn btn-info" id="BtnAsignarTicket" data-dismiss="modal" style="">
				<i class="ace-icon fa fa-user bigger-150"></i>
				Asignar Ticket
				</button>
				<button class="btn btn-white btn-secondary " id="BtnCerrarAnular" data-dismiss="modal" style="">
				Cerrar
				</button>
			</div>
		</div>
	</div>
</div>