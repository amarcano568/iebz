<form id="form_register_usuario" method="post" enctype="multipart/form-data" action="registrar-usuario">
  @csrf
  <!-- Modal -->
  <div class="modal fade" id="modal-usuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="x_content">
            <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Role</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="x_content">
                  <br />
                  <input type="text" name="idUsuario" id="idUsuario" style="display: none;">
                  <div class="col-md-6 col-sm-6  form-group has-feedback">
                    <input type="text" class="form-control has-feedback-left" id="nombres" name="nombres" placeholder="Nombres">
                    <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                  </div>
                  <div class="col-md-6 col-sm-6  form-group has-feedback">
                    <input type="text" class="form-control"  id="apellidos" name="apellidos" placeholder="Apellidos">
                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                  </div>
                  <div class="col-md-6 col-sm-6  form-group has-feedback">
                    <input type="email" class="form-control has-feedback-left"  id="correo" name="correo" placeholder="Email">
                    <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                  </div>
                  <div class="col-md-6 col-sm-6  form-group has-feedback">
                    <input type="text" class="form-control"  id="telefono" name="telefono" placeholder="Teléfono">
                    <span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span>
                  </div>
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Cargo</label>
                    <div class="col-md-9 col-sm-9 ">
                      <select id="cargo" name="cargo" class="chosen-select">
                        @foreach( $cargos as $cargo )
                        <option value="{{$cargo->idCargo}}">
                          {{$cargo->descCargo}}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Rol</label>
                    <div class="col-md-9 col-sm-9 ">
                      <select  id="rol" name="rol" class="chosen-select">
                        <option value="usu">Usuario</option>
                        <option value="sup">Supervisor de Sistemas</option>
                        <option value="adm">Administrador</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Status<span class="required">*</span>
                  </label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="">
                      <label>
                        <input type="checkbox" id="status" name="status" class="js-switch"  />
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <h3>Lista de roles</h3>
              {{-- <div class="form-group">
                <ul class="list-unstyled">
                  @foreach($roles as $role)
                    <li>
                        <label>
                        {{ Form::checkbox('roles[]', $role->id, null) }}
                        {{ $role->name }}
                        <em>({{ $role->description }})</em>
                        </label>
                    </li>
                    @endforeach
                  </ul>
              </div> --}}
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