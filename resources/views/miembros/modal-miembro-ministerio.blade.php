<div id="ModalIncluirMiembroMinisterio" class="modal fade" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-xs modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header modal-header-info">
        <h4 class="modal-title" ><i class="far fa-user"></i> Incluir Miembro al Ministerio de <span id="tituloModal"></span>.</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" >
        <div class="x_content" >
          <input type="text" name="idMinisterio" id="idMinisterio" style="display: none;">
          <div class="row">
            <select class="chosen-select" id="miembrosIncluir" name="miembrosIncluir" data-placeholder="Seleccione un Miembro..." required="">
            
            </select>
          </div>
          <div class="row" id="fotoMiembro">
              <img src="images/user.png" alt="Foto Miembro" width="100px" height="125px" class="img-thumbnail center">
          </div>
          <br>

        </div>
        
      </div>
      <div class="modal-footer modal-footer-danger">
        <button id="btnIncluirMiembro" type="button" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Incluir Miembro</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>