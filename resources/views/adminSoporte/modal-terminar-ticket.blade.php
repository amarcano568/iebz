<div id="ModalTerminalTicket" class="modal fade" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header modal-header-info">
        <h4 class="modal-title text-white" ><i class="fas fa-fast-forward"></i> Terminar Ticket </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" >
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <h6 class="modal-title" >Tipo de Atención</h6>
            <select data-placeholder="Seleccione Tipo de Atención..." class="form-control chosen-select" id="TipoAtencion" name="TipoAtencion" required style="width: 100%" required="">
              @foreach( $tipoAtencion as $atencion )
              <option value="{{$atencion->idTabla}}">
                {{$atencion->desTabla}}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <h6 class="modal-title" >Tipo de Solución</h6>
            <select data-placeholder="Seleccione una Solución..." class="form-control chosen-select" id="Problemas" name="Problemas" required style="width: 100%" required="">
              
            </select>
            <a href="" id="btnAddSolucion" style="float: right;">
              <i class="text-success fas fa-plus-circle"></i>
            </a>
            
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="hero-unit">
              <div id="alerts"></div>
              <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
                <div class="btn-group">
                  <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fas fa-font"></i><b class="caret"></b></a>
                  <ul class="dropdown-menu">
                  </ul>
                </div>
                <div class="btn-group">
                  <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fas fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
                    <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
                    <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
                  </ul>
                </div>
                <div class="btn-group">
                  <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fas fa-bold"></i></a>
                  <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fas fa-italic"></i></a>
                  <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fas fa-strikethrough"></i></a>
                  <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fas fa-underline"></i></a>
                </div>
                <div class="btn-group">
                  <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fas fa-list-ul"></i></a>
                  <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fas fa-list-ol"></i></a>
                  <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fas fa-indent"></i></a>
                </div>
                <div class="btn-group">
                  <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fas fa-align-left"></i></a>
                  <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fas fa-align-center"></i></a>
                  <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fas fa-align-right"></i></a>
                  <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fas fa-align-justify"></i></a>
                </div>
  <!--               <div class="btn-group">
                  <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fas fa-link"></i></a>
                  <div class="dropdown-menu input-append">
                    <center>
                    <input class="form-control" placeholder="URL" type="text" data-edit="createLink"/>
                    <button class="btn btn-primary btn-sm" type="button"><i class="far fa-check-circle"></i></button>
                    </center>
                  </div>
                  <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fas fa-cut"></i></a>
                </div> -->
                
                <div class="btn-group">
                  <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="far fa-image"></i></a>
                  <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                </div>
                <div class="btn-group">
                  <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fas fa-undo"></i></a>
                  <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fas fa-redo-alt"></i></a>
                </div>
                <input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
              </div>
              <div id="editor" style="height: 12em !important">
                
              </div>
              <textarea name="problema" id="problema" style="display:none;"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h6 class="modal-title" >Equipo relacionado</h6>
            <select data-placeholder="Seleccione el equipo relacionado..." class="form-control chosen-select" id="Equipo" name="Equipo" required style="width: 100%" required="">
              <option value=""></option>
              @foreach( $equipos as $equipo )
              <option value="{{$equipo->idActivo}}">
                Codigo => {{$equipo->codigoActivo}} Nro. Serie => {{$equipo->nroSerie}} => {{$equipo->descripcion}}
              </option>
              @endforeach
            </select>
            <a href="" id="btnAddEquipo" style="float: right;">
              <i class="text-success fas fa-plus-circle"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="modal-footer modal-footer-danger">
        <button type="submit" class="btn btn-success" id="BtnTerminarTicket"><i class="fas fa-fast-forward"></i> Terminar</button>
        <button type="submit" class="btn btn-warning" id="BtnAdjuntarTicket"><i class="fas fa-paperclip"></i> Adjuntar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>