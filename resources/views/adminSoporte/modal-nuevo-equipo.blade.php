<form id="FormModalNuevoActivo" method="post" enctype="multipart/form-data" action="registrar-nuevo-activo">
  @csrf
  <div id="ModalNuevoEquipo" class="modal fade" data-backdrop="static" data-keyboard="false" style="z-index: 99999;width: 95%" >
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header modal-header-warning">
          <h4 class="modal-title text-white" ><i class="fas fa-plus"></i> Nuevo Equipo (Activo)</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" >
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <label>Nueva solución</label>
              <select id="ActivoTipo" class="form-control chosen-select" required="">
                <option value="">Seleccione Tipo</option>
                <option value="1">Hardware</option>
                <option value="2">Software</option>
              </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <label>Código de Control</label>
              <input type="text" id="CodActivo" name="CodActivo" class="form-control" placeholder="Código del Activo" aria-required="true" required="">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <label>Serie</label>
              <input type="text" id="SerActivo" name="SerActivo" class="form-control" placeholder="Serie del Activo" aria-required="true" required="">
            </div>
          </div>
          <div class="row">
            <div class="form-group  col-lg-12 col-md-12 col-sm-12 col-sm-12">
              <label>Descripción</label>
              <div class="clearfix">
                <input type="text" id="DesActivo" name="DesActivo" class="form-control" placeholder="Descripción del Activo" aria-required="true" required="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-sm-12">
              <label>Descripción Completa</label>
              <div class="clearfix">
                <textarea id="DescActCompleta" name="DescActCompleta" class="form-control" rows="2" maxlength="1000" placeholder="Por favor escriba una Descripción completa para este Activo" required=""></textarea>
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