<form id="FormMinisterio" method="post" enctype="multipart/form-data" action="registrar-ministerio">
  @csrf
  <div id="ModalMinisterio" class="modal fade" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-xs modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header modal-header-info">
          <h4 class="modal-title" ><i class="fas fa-project-diagram"></i> <span id="tituloModalMinisterio"></span>.</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" >
          <div class="x_content" >
            <input type="text" name="idMinisterioAgregar" id="idMinisterioAgregar" style="display: none;">
            <input type="text" name="nivelNuevoMinisterio" id="nivelNuevoMinisterio" style="display: none;">
            <input type="text" name="idNuevoMinisterio" id="idNuevoMinisterio" style="display: none;">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                <div class="form-label-group">
                  <input type="text" id="nombreMinisterio" name="nombreMinisterio" class="form-controls" placeholder="Nombre del Ministerio" style="width: 100%;">
                  <label for="nombreMinisterio">Nombre del Ministerio</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <select class="chosen-select" id="statusMinisterio" name="statusMinisterio" data-placeholder="Seleccione Status..." required="">
                  <option value='1'>Activo</option>
                  <option value='0'>Inactivo</option>
                </select>
              </div>
            </div>
          </div>
          
        </div>
        <div class="modal-footer modal-footer-danger">
          <button id="" type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Guardar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</form>