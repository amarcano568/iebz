<!--
Ventana Modal para Agregar Categorias
-->
<form id="ModalArea" method="post" enctype="multipart/form-data" action="registrar-area">
    @csrf
  <div id="ModalAgregarArea" class="modal fade" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">          
          <h4 class="modal-title" id="TituloModalReferencia"><p id="TitleModal"></p></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" >
          <div class="row form-group" id="DivIdArea" style="display: none;">
            <div class="col-sm-1 col-xs-1 col-lg-2"></div>
            <div class="col-sm-4 col-xs-2 col-lg-6">
              <label class="control-label no-padding-right" for="InputNombrePerfil"> Id Área </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="ace-icon fa fa-list-ol"></i></span>
                </div>
                <input type="text" id="IdAreaX" name="IdAreaX" readonly class="form-control red">
              </div>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-1 col-xs-1 col-lg-2"></div>
            <div class="col-sm-8 col-xs-2 col-lg-9">
              <label class="control-label no-padding-right" for="InputNombrePerfil"> Nombre del Área </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="ace-icon fa fa-user"></i></span>
                </div>
                <input type="text" id="InputNombreArea" name="InputNombreArea" required class="form-control">
              </div>
            </div>
          </div>
          
          <div class="row form-group" id="StatusArea" >
            <div class="col-sm-1 col-xs-1 col-lg-2"></div>
            <div class="col-sm-5 col-xs-6 col-lg-5">
              <label class="control-label no-padding-right"  for="form-field-select-1">Status del Área</label>
              <div class="clearfix">
                <select class="form-control chosen-select" id="SelectStatus" name="SelectStatus" required style="width: 100%">
                  <option value='' selected>Elija una Opción</option>
                  <option value='1'>Activo</option>"
                  <option value='0'>Inactivo</option>"
                </select>
              </div>
            </div>
          </div>
          
        </div>
        <div class="modal-footer modal-footer-danger">
          <button class="btn btn-white btn-success btn-round" type="submit" id="BtnAgregarUsuario" style="">
          <i class="ace-icon fa fa-floppy-o green"></i>
          Guardar
          </button>
          <button class="btn btn-white btn-warning btn-round" id="BtnModalCerrarVentanaPdf" data-dismiss="modal" style="">
          <i class="ace-icon fa fa-window-close-o red"></i>
          Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</form>