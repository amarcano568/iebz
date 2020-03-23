<!-- Modal -->
<div class="modal fade" id="ModalMantCargo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<form id="FormMantCargo" method="post" enctype="multipart/form-data" action="registrar-cargo">
  @csrf
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
            
            <div class="panel-body">
              <div class="row">
                  <input type="hidden"  name="idCargo" id="idCargo" >
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                  <label>Descripción</label>
                  <div class="clearfix">
                    <input type="text" class="form-control" name="descCargo" id="descCargo" >
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                  <label>Status</label>
                  <div class="clearfix">
                    <select data-placeholder="Seleccione status..." id="statusCargo" name="statusCargo" class="form-control  chosen-select">
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
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
  </form>
</div>
