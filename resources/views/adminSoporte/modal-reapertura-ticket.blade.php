<form id="FormReaperturaTicket" method="post" enctype="multipart/form-data" action="reapertura-ticket">
  @csrf
  <div id="ModalReaperturaTicket" class="modal fade" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-md shadow-lg ">
      <div class="modal-content">
        <div class="modal-header modal-header-primary">
          <h4 class="modal-title text-white" ><i class="far fa-folder-open"></i> Reapertura Ticket </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" >
          <textarea id="DescripcionReapertura" name="DescripcionReapertura" class="form-control" rows="5" required maxlength="4000"></textarea>
        </div>
        <div class="modal-footer modal-footer-danger">
          <button type="submit" class="btn btn-success" ><i class="far fa-folder-open"></i> Reaperturar Ticket</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</form>