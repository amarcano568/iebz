<form id="FormCambiarArea" method="post" enctype="multipart/form-data" action="cambiar-area-ticket">
  @csrf
  <div id="ModalCambiarArea" class="modal fade" data-backdrop="static" data-keyboard="false" style="z-index: 99999;" >
    <div class="modal-dialog modal-xs modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header modal-header-warning">
          <h4 class="modal-title text-white" ><i class="fas fa-exchange-alt"></i> Reasignar Ticket </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" >
          <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Área de Atención</label>
              <select data-placeholder="Seleccione una Categoría..." class="form-control chosen-select" id="Area" name="Area" required style="width: 100%" >
                @foreach( $areas as $area )
                <option value="{{$area->idArea}}">
                  {{$area->descArea}}
                </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Tipo</label>
              <select data-placeholder="Seleccione el Tipo de Ticket..." class="form-control chosen-select" id="TipoTicket" name="TipoTicket" required style="width: 100%" >
              </select>
              
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>Categoría</label>
              <select data-placeholder="Seleccione la categoría del Ticket..." class="form-control chosen-select" id="CategoriaX" name="CategoriaX" required style="width: 100%" >
              </select>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <label>SubCategoría</label>
              <select data-placeholder="Seleccione la categoría del Ticket..." class="form-control chosen-select" id="SubCategoria" name="SubCategoria" style="width: 100%">
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Comentario</label>
            <textarea id="DescripcionReAsignar" name="DescripcionReAsignar" class="form-control" rows="3" required="" maxlength="1000" aria-required="true"></textarea>
          </div>
        </div>
        <div class="modal-footer modal-footer-danger">
          <button type="submit" class="btn btn-success">Reasignar Ticket</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</form>