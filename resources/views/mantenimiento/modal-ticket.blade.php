<form id="FormTipoTicket" method="post" enctype="multipart/form-data" action="registrar-mant-ticket">
  @csrf
  <!-- Modal -->
<div class="modal fade" id="ModalTipoTicket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="panel panel-success">
            <div class="panel-heading">Datos del tipo de ticket</div>
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                  <label>Tipo</label>
                  <div class="clearfix">
                    <input type="text" class="form-control" name="tipo" id="tipo" readonly="">
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                  <label>Id</label>
                  <div class="clearfix">
                    <input type="text" class="form-control" name="idTipo" id="idTipo" readonly="">
                  </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                  <label>Descripción</label>
                  <div class="clearfix">
                    <input type="text" class="form-control" name="descTipo" id="descTipo" >
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                  <label>Status</label>
                  <div class="clearfix">
                    <select data-placeholder="Seleccione status..." id="statusTicket" name="statusTicket" class="form-control  chosen-select">
                      <option value=""></option>
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="interno" id="interno">
                    <label class="custom-control-label" for="interno">Interno</label>
                  </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="externo" id="externo">
                    <label class="custom-control-label" for="externo">Externo</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div id="esperaArea" >
                    <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i> Cargando Áreas..
                  </div>
                  <div id="divChosenAreas" style="display: none;" class="form-group">
                    <label class="control-label" for="form-field-1"> Áreas </label>
                    <div class="clearfix">
                      <select required multiple class="clearfix chosen-select form-control" id="chosenAreas" name="chosenAreas[]" style="width: 200%;" data-placeholder="Seleccione las Areas para el tipo de ticket....">
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">        
        <button type="submit" class="btn btn-success"><i class="far fa-save"></i> Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</form>