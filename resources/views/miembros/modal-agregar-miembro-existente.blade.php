<div id="ModalAgregarMiembroExistente" class="modal fade" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-xs modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header modal-header-info">
        <h4 class="modal-title" ><i class="far fa-user"></i> Agregar Miembro Existente.</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" >
        <div class="x_content" >
          <div class="row">
            <select class="chosen-select" id="miembroToRelacion" name="miembroToRelacion" data-placeholder="Seleccione un Familiar..." required="">
            
            </select>
          </div>
          <br>
          <div class="row">
              <select class="chosen-select" id="parentesco" name="parentesco" data-placeholder="Seleccione el Parentesco..." required="">
              <option></option>
              <option value="Conyuge">Conyuge</option>
              <option value="Padre">Padre</option>
              <option value="Madre">Madre</option>
              <option value="Hijo(a)">Hijo(a)</option>
            </select>
          </div>
        </div>
        
      </div>
      <div class="modal-footer modal-footer-danger">
        <button id="btnAgregarRelacion" type="button" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Agregar Familiar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>