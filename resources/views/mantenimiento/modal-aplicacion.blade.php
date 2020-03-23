<form id="FormModalAplicacion" method="post" enctype="multipart/form-data" action="registrar-aplicacion">
  @csrf
    <div id="ModalAgregarAplicacion" class="modal fade" data-backdrop="static" data-keyboard="false" >
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="TituloModalSubCategoria"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span id="TitleModalAplicacion"></span></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>            
          </div>
          <div class="modal-body" >
            <input type="text" id="idAplicacion" name="idAplicacion" readonly class="form-control" style="display: none;">
            <div class="row form-group" id="DivIdCategoria">
              <div class="col-sm-1 col-xs-1 col-lg-2 col-md-2"></div>
              <div class="col-sm-2 col-xs-2 col-lg-6 col-md-6">
                <label class="control-label no-padding-right" for="InputNombrePerfil"> Id SubCategoría </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-certificate"></i></span>
                  </div>
                  <input type="text" id="IdSubCategoriaAplicacion" name="IdSubCategoriaAplicacion" readonly class="form-control">
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-1 col-xs-1 col-lg-2"></div>
              <div class="col-sm-10 col-xs-12 col-lg-10">
                <label class="control-label no-padding-right" for="InputNombrePerfil"> Nombre de la Aplicación </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fab fa-windows"></i></span>
                  </div>
                  <input type="text" id="InputNombreAplicacion" name="InputNombreAplicacion" required class="form-control">
                </div>
              </div>
            </div>
            
            <div class="row form-group" id="StatusSubCategoria">
              <div class="col-sm-1 col-xs-1 col-lg-2"></div>
              <div class="col-sm-5 col-xs-6 col-lg-5">
                <label class="control-label no-padding-right"  for="form-field-select-1">Status de la Aplicacion</label>
                <div class="clearfix">
                  <select class="form-control" id="SelectStatusAplicacion" name="SelectStatusAplicacion" required style="width: 100%">
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