<form id="FormDescripcionProblema" method="post" enctype="multipart/form-data">
<div class="container">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <label>TITULO</label>
      <div class="clearfix">
        <input type="text" id="Titulo" name="Titulo" class="form-control" required placeholder="Por favor escriba una línea de asunto" required="" />
      </div>
    </div>
  </div>
  <div class="hero-unit">
    <div id="alerts"></div>
    <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Fuente"><i class="fas fa-font"></i><b class="caret"></b></a>
        <ul class="dropdown-menu">
        </ul>
      </div>
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Tamaño de la fuente."><i class="fas fa-text-height"></i>&nbsp;<b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
          <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
          <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
        </ul>
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="bold" title="Negrita (Ctrl/Cmd+B)"><i class="fas fa-bold"></i></a>
        <a class="btn" data-edit="italic" title="Italica (Ctrl/Cmd+I)"><i class="fas fa-italic"></i></a>
        <a class="btn" data-edit="strikethrough" title="Tachado"><i class="fas fa-strikethrough"></i></a>
        <a class="btn" data-edit="underline" title="Subrayar (Ctrl/Cmd+U)"><i class="fas fa-underline"></i></a>
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="insertunorderedlist" title="Lista de viñetas"><i class="fas fa-list-ul"></i></a>
        <a class="btn" data-edit="insertorderedlist" title="Lista de números"><i class="fas fa-list-ol"></i></a>
        <a class="btn" data-edit="outdent" title="Reducir sangría (Shift+Tab)"><i class="fas fa-indent"></i></a>
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="justifyleft" title="Alinear a la izquierda (Ctrl/Cmd+L)"><i class="fas fa-align-left"></i></a>
        <a class="btn" data-edit="justifycenter" title="Centrar (Ctrl/Cmd+E)"><i class="fas fa-align-center"></i></a>
        <a class="btn" data-edit="justifyright" title="Alinear a la derecha (Ctrl/Cmd+R)"><i class="fas fa-align-right"></i></a>
        <a class="btn" data-edit="justifyfull" title="Justificar (Ctrl/Cmd+J)"><i class="fas fa-align-justify"></i></a>
      </div>
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hipervínculo"><i class="fas fa-link"></i></a>
        <div class="dropdown-menu input-append">
          <center>
          <input class="form-control" placeholder="URL" type="text" data-edit="createLink"/>
          <button class="btn btn-primary btn-sm" type="button"><i class="far fa-check-circle"></i></button>
          </center>
        </div>
        <a class="btn" data-edit="unlink" title="Eliminar hipervínculo"><i class="fas fa-cut"></i></a>
      </div>
      
      <div class="btn-group">
        <a class="btn" title="Insertar imagen (o simplemente arrastrar y soltar)" id="pictureBtn"><i class="far fa-image"></i></a>
        <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="undo" title="Deshacer (Ctrl / Cmd + Z)"><i class="fas fa-undo"></i></a>
        <a class="btn" data-edit="redo" title="Rehacer (Ctrl / Cmd + Y)"><i class="fas fa-redo-alt"></i></a>
      </div>
      <input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
    </div>
    <div id="editor">
      
    </div>
    <textarea name="problema" id="problema" style="display:none;"></textarea>
  </div>
</div>