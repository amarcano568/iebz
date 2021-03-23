<form id="FormMiembro" method="post" enctype="multipart/form-data" action="registrar-miembro">
  @csrf
  <div class="row">
    <div class="col-md-12 col-sm-12 ">
      <div class="x_panel">
        <div class="x_title">
          <h2>Miembro <small>Datos personales.</small></h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
              <div class="row">
                <div class="col-lg-2 col-md-1 col-sm-6 col-xs-6">
                  <h2><small>Iglesia</small></h2>
                </div>
                <div class="col-lg-4  col-md-2 col-sm-6 col-xs-6">
                  <select class="chosen-select" id="idIglesia" name="idIglesia" data-placeholder="Seleccione una Iglesia..." required="">
                    @foreach( $iglesias as $iglesia )
                    <option value="{{$iglesia->id}}">
                      {{$iglesia->nombreCorto}}
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-2 col-md-1 col-sm-6 col-xs-6">
                  <h2><small>Status</small></h2>
                </div>
                <div class="col-lg-4  col-md-2 col-sm-6 col-xs-6">
                  <select class="chosen-select" id="status" name="status" data-placeholder="Seleccione un status...">
                    @foreach( $status as $statu )
                    <option value="{{$statu->id}}">
                      {{$statu->nombre}}
                    </option>
                    @endforeach
                  </select>
                </div>
                
              </div>
            </li>
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="#">Settings 1</a>
              <a class="dropdown-item" href="#">Settings 2</a>
            </div>
          </li>
          <li><a class="close-link"><i class="fa fa-close"></i></a>
        </li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="col-md-3 col-sm-3  profile_left">
        <center>
        <div class="profile_img">
          <div id="crop-avatar">
            <!-- Current avatar -->
            <div id="fotoUsuario">
              <i class="img-thumbnail fa-8x far fa-user img-responsive avatar-view"></i>  
            </div>
            
          </div>
        </div>
        </center>
        <h3 id="nombreCorto">Nombre</h3>
        <ul class="list-unstyled user_data">
          <li>
            <i class="fa fa-briefcase user-profile-icon"></i> <span id="profesionCorto"></span>
          </li>
        </ul>
        <button id="btnAgregarFoto" data-toggle="modal" data-target="#ModalAgregarFoto" type="button" class="btn btn-outline-info"><i class="fas fa-camera-retro"></i> Agregar Fotografía</button>
        <br>
      </div>
      <div class="col-md-9 col-sm-9 ">
        
        <div style="float: right;">
          <button type="submit" class="btn btn-outline-success"><i class="far fa-save"></i> Guardar</button>
          <button type="button" class="btn btn-outline-danger" id="btnCerrar"><i class="far fa-times-circle"></i> Cerrar</button>
        </div>
        
        <div class="x_panel">
          <input type="text" name="idMiembro" id="idMiembro" style="display: none;">
          <div class="x_content">
            <div class="row">
              <div class="form-group input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Nombre" aria-label="Nombre" aria-describedby="basic-addon1" id="nombre" name="nombre">
              </div>
              <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Apellido 1" aria-label="Apellido 1" aria-describedby="basic-addon1"  id="apellido1" name="apellido1" >
              </div>
              <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Apellido 2" aria-label="Apellido 2" aria-describedby="basic-addon1"  id="apellido2" name="apellido2">
              </div>
            </div>
            <div class="row">
              <div class="input-group  col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" id="email" name="email">
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="container">
                  <div class="input-group my-group">
                    <select id="tipoDoc" name="tipoDoc" class="form-control" style="width: 2px">
                      <option value="DNI">DNI</option>
                      <option value="NIE">NIE</option>
                      <option value="PAS">PAS</option>
                    </select>
                    <input type="text" class="form-control"  id="nroDocumento" name="nroDocumento" />
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <select id="sexo" name="sexo" class="form-control chosen-select" >
                  <option value="M">Masculino</option>
                  <option value="F">Femenino</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="input-group  col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-list-ol"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Código Postal" aria-label="Código Postal" aria-describedby="basic-addon1"  id="codPostal" name="codPostal">
              </div>
              <div class="input-group col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-signs"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Dirección" aria-label="Dirección" aria-describedby="basic-addon1" id="direccion" name="direccion">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <select id="comunidad" name="comunidad" class="chosen-select" data-placeholder="Seleccione una Comunidad..." >
                  <option></option>
                  @foreach( $comunidades as $comunidad )
                  <option value="{{$comunidad->id}}">
                    {{$comunidad->nombre}}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <select id="provincia" name="provincia" class="chosen-select" data-placeholder="Seleccione una Provincia..." >
                  <option></option>
                  @foreach( $provincias as $provincia )
                  <option value="{{$provincia->id}}">
                    {{$provincia->nombre}}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-label-group">
                  <input type="text" id="poblacion" name="poblacion" class="form-controls" placeholder="Población" >
                  <label for="nombreEmpresa">Población</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="input-group  col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Teléfono" aria-label="Teléfono" aria-describedby="basic-addon1" id="telFijo" name="telFijo">
              </div>
              <div class="input-group  col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-mobile-alt"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Movil" aria-label="Movil" aria-describedby="basic-addon1" id="telMovil" name="telMovil">
              </div>
              <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="date" class="form-control" placeholder="F. Nacimiento" aria-label="F. Nacimiento" aria-describedby="basic-addon1" id="fecNacimiento" name="fecNacimiento">
              </div>
              <div class="input-group  col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-birthday-cake"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Edad" aria-label="F. Nacimiento" aria-describedby="basic-addon1" readonly id="edad" name="edad">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-label-group">
                  <input type="text" id="lugNacimiento" name="lugNacimiento" class="form-controls" placeholder="Lugar de Nacimiento" aria-invalid="false" style="width: 100%">
                  <label for="nombreEmpresa">Lugar de Nacimiento</label>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <select id="pais" name="pais" class="chosen-select" data-placeholder="Seleccione un Pais..." >
                  <option></option>
                  @foreach( $paises as $pais )
                  <option value="{{$pais->id}}">
                    {{$pais->nombre}}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <select id="profesion" name="profesion" class="chosen-select" data-placeholder="Seleccione una Profesión...">
                  <option value=""></option>
                  <option value="0">No Tiene Profesión</option>
                  @foreach( $profesiones as $profesion )
                  <option value="{{$profesion->id}}">
                    {{$profesion->nombre}}
                  </option>
                  @endforeach
                </select>
                <a href="" id="BtnNuevo"><i class="float-right text-primary fas fa-plus-circle"></i></a>
              </div>
            </div>            
            <div class="row">
              <div class="input-group  col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i>&nbsp;Bautismo</span>
                </div>
                <input type="date" class="form-control" placeholder="F. Bautismo" aria-label="F. Bautismo" aria-describedby="basic-addon1" id="fecBautismo" name="fecBautismo">
              </div>
              <div class="input-group  col-lg-7 col-md-7 col-sm-7 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-church"></i>&nbsp;Bautismo</span>
                </div>
                <input type="text" class="form-control" placeholder="Iglesia Bautismo" aria-label="Iglesia Bautismo" aria-describedby="basic-addon1" id="iglesiaBautismo" name="iglesiaBautismo" >
              </div>
            </div>
            <div class="row">
              <div class="input-group  col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i>&nbsp;C. Traslado</span>
                </div>
                <input type="date" class="form-control" placeholder="F. Traslado" aria-label="F. Traslado" aria-describedby="basic-addon1" id="cartaTraslado" name="cartaTraslado">
              </div>
              <div class="input-group  col-lg-7 col-md-7 col-sm-7 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-church"></i>&nbsp;Procedencia</span>
                </div>
                <input type="text" class="form-control" placeholder="Iglesia Procedencia" aria-label="Iglesia Procedencia" aria-describedby="basic-addon1" id="iglesiaProcedencia" name="iglesiaProcedencia">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-lg-12">
                <label for="exampleFormControlTextarea1">Otros datos de interes</label>
                <textarea class="form-control" row="3"  id="otrosDatos" name="otrosDatos"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="input-group  col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i>&nbsp;Fecha de alta</span>
                </div>
                <input type="date" class="form-control" placeholder="F. Alta" aria-label="F. Alta" aria-describedby="basic-addon1" id="fecAlta" name="fecAlta">
              </div>
              <div class="input-group  col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i>&nbsp;Fecha de baja</span>
                </div>
                <input type="date" class="form-control" placeholder="F. Baja" aria-label="F. Baja" aria-describedby="basic-addon1" id="fecBaja" name="fecBaja">
              </div>
            </div>
            <div class="ln_solid"></div>
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="">
                  <label>
                    <input id="datos_personales" name="datos_personales" type="checkbox" class="form-control"  /> Autorización uso de datos personales.
                  </label>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="">
                  <label>
                    <input id="imagenes_personales" name="imagenes_personales" type="checkbox" class="form-control"  /> Autorización tratamiento de imagen personal.
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
</div>
</form>