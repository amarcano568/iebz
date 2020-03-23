<form id="ModalCategoria" method="post" enctype="multipart/form-data" action="registrar-categoria">
  @csrf
  <div id="ModalAgregarCategoria" class="modal fade" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-info" id="TituloModalReferencia"> <span id="TitleModal"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>          
        </div>
        <div class="modal-body" >
          <div class="row form-group" id="DivIdCategoria" style="display: none;">
            <div class="col-sm-1 col-xs-1 col-lg-2"></div>
            <div class="col-sm-4 col-xs-2 col-lg-6">
              <label class="control-label no-padding-right blue" for="InputNombrePerfil"> Id Categoría </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-list-ol"></i></span>
                </div>
                <input type="text" id="IdCategoria" name="IdCategoria" readonly class="form-control red">
              </div>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-1 col-xs-1 col-lg-2"></div>
            <div class="col-sm-8 col-xs-2 col-lg-10">
              <label class="control-label no-padding-right" for="InputNombrePerfil"> Nombre de la Categoría </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-signature"></i></span>
                </div>
                <input type="text" id="InputNombreCategoria" name="InputNombreCategoria" required class="form-control">
              </div>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-1 col-xs-1 col-lg-2"></div>
            <div class="col-sm-5 col-xs-6 col-lg-6">
              <label class="control-label no-padding-right"  for="form-field-select-1">Área</label>
              <div class="clearfix">
                
                <select data-placeholder="Seleccione una Categoría.." class="form-control chosen-select" id="SelectArea" name="SelectArea" required style="width: 100%">
                 @foreach( $areas as $area )
                  <option value="{{$area->idArea}}">
                    {{$area->descArea}}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-1 col-xs-1 col-lg-2"></div>
            <div class="col-sm-5 col-xs-6 col-lg-6">
              <label class="control-label no-padding-right"  for="form-field-select-1">Tipo de ticket</label>
              <div class="clearfix">
                <select data-placeholder="Seleccione un Tipo.." class="form-control chosen-select" id="SelectTipo" name="SelectTipo" required style="width: 100%">
                  @foreach( $tipTickets as $tipTicket )
                  <option value="{{$tipTicket->idTabla}}">
                    {{$tipTicket->desTabla}}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          
          <div class="row form-group" id="StatusCategoria" style="display: none;">
            <div class="col-sm-1 col-xs-1 col-lg-2"></div>
            <div class="col-sm-5 col-xs-6 col-lg-5">
              <label class="control-label no-padding-right"  for="form-field-select-1">Status de la Categoría</label>
              <div class="clearfix">
                <select class="form-control chosen-select" id="SelectStatus" name="SelectStatus" required style="width: 100%">
                  <option value='1'>Activo</option>"
                  <option value='0'>Inactivo</option>"
                </select>
              </div>
            </div>
          </div>
          
        </div>
        <div class="modal-footer modal-footer-danger">
          <button type="submit" class="btn btn-success"><i class="far fa-save"></i> Guardar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</form>