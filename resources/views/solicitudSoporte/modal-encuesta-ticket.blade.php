<form id="FormEncuestaTicket" method="post" enctype="multipart/form-data" action="encuesta-ticket">
  @csrf
  <div id="ModalEncuestaTicket" class="modal fade" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-lg  modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header modal-header-success">
          <h4 class="modal-title text-white" ><i class="far fa-star"></i> Encuesta del soporte recibido </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" >
          <input id="estrellas" value="0" type="text" class="rating" data-min=0 data-max=5 data-step=1 data-size="lg" title="">
          <label>Breve comentario acerca del Servicio Recibido:</label>
          <textarea id="DescripcionEncuesta" name="DescripcionEncuesta" class="form-control" rows="5" required maxlength="4000"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnEnviarEncuesta" class="btn btn-success" ><i class="far fa-star"></i> Enviar Encuesta</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</form>