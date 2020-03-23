<form id="form-maestro-empresa" method="post" enctype="multipart/form-data" action="registrar-maestro-empresa">
  @csrf
  <!-- Modal -->
  <div class="modal fade" id="modal-maestro-empresa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12  col-md-12 ">
              <div class="x_panel">
                <h5 class="text-info"><i class="far fa-building"></i> Datos de la Empresa</h5>
                <div class="row">
                  <input type="text" id="idEmpresa" name="idEmpresa" style="display: none;">
                  <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                    <div class="form-label-group">
                      <input type="text" id="nombreEmpresa" name="nombreEmpresa" class="form-control" placeholder="Nombre de la Empresa" required="" autofocus="">
                      <label for="nombreEmpresa">Nombre de la Empresa</label>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-label-group">
                      <input type="text" id="nroRuc" name="nroRuc" class="form-control" placeholder="Nro. de Ruc">
                      <label for="nroRuc">Nro. de Ruc</label>
                    </div>
                  </div>       
                  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="form-label-group">
                      <input type="text" id="baseDatos" name="baseDatos" class="form-control" placeholder="Base de Datos">
                      <label for="baseDatos"><i class="fas fa-database"></i> Base de Datos</label>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="form-label-group">
                      <input type="number" id="usuariosPermitidos" name="usuariosPermitidos" class="form-control" placeholder="Usuarios Permitidos">
                      <label for="usuariosPermitidos"><i class="fas fa-users"></i> Usuarios Permitidos</label>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="form-label-group">
                      <input type="text" id="telFijo" name="telFijo" class="form-control" placeholder="Teléfono fijo" >
                      <label for="telFijo"><i class="fas fa-phone"></i> Teléfono fijo</label>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="form-label-group">
                      <input type="text" id="telMovil" name="telMovil" class="form-control" placeholder="Teléfono Movil">
                      <label for="telMovil"><i class="fas fa-mobile-alt"></i> Teléfono Movil</label>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-label-group">
                      <input type="email" id="emailEmpresa" name="emailEmpresa" class="form-control" placeholder="Correo electrónico">
                      <label for="emailEmpresa"><i class="far fa-envelope"></i> Correo electrónico</label>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-label-group">
                      <input type="text" id="representante" name="representante" class="form-control" placeholder="Representante" required="" autofocus="">
                      <label for="representante"><i class="fas fa-user-tie"></i> Representante</label>
                    </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-label-group">
                      <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirección" required="" autofocus="">
                      <label for="direccion"><i class="fas fa-map-signs"></i> Dirección</label>
                    </div>
                  </div>
                </div>
                <hr style="margin-top: 0em;border-top: 1px dotted #0099CC;">
                <h5 class="text-info"><i class="far fa-user"></i> Datos del Usuario Administrador</h5>
                <div class="x_content">
                  <input type="text" name="idUsuario" id="idUsuario" style="display: none;">
                  <div class="col-md-6 col-sm-6  form-group has-feedback">
                    <input type="text" class="form-control has-feedback-left" id="nombresUsuario" name="nombresUsuario" placeholder="Nombres">
                    <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                  </div>
                  <div class="col-md-6 col-sm-6  form-group has-feedback">
                    <input type="text" class="form-control"  id="apellidosUsuario" name="apellidosUsuario" placeholder="Apellidos">
                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                  </div>
                  <div class="col-md-6 col-sm-6  form-group has-feedback">
                    <input type="email" class="form-control has-feedback-left"  id="correoUsuario" name="correoUsuario" placeholder="Email">
                    <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                  </div>
                  <div class="col-md-6 col-sm-6  form-group has-feedback">
                    <input type="text" class="form-control"  id="telefonoUsuario" name="telefonoUsuario" placeholder="Teléfono">
                    <span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="BtnSaveNewEmpresa" type="submit" class="btn btn-success"><i class="far fa-building"></i> Crear Empresa</button>
          <button id="BtnSaveActualizarEmpresa" type="button" class="btn btn-success"><i class="far fa-building"></i> Actualizar Empresa</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</form>