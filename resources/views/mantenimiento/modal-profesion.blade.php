<!-- Modal -->
<div class="modal fade" id="ModalMantProfesion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form id="FormMantProfesion" method="post" enctype="multipart/form-data" action="registrar-profesion">
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
          
          <div class="row">
            <input type="hidden"  name="idProfesion" id="idProfesion" >
            
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 form-group">
              <label>Nombre</label>
              <div class="clearfix">
                <input type="text" class="form-control" name="nombre" id="nombre" >
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
              <label>Status</label>
              <div class="clearfix">
                <select data-placeholder="Seleccione status..." id="status" name="status" class="form-control  chosen-select">
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
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