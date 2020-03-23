<div id="ModalDetalleTicket" class="modal fade" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-xl  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-info"> '<i class="fas fa-ticket-alt"></i> Detalles del Ticket Nro. <span id="TituloNroTicket"></span> </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" >
        <input type="text" name="IdEstadoAnterior" id="IdEstadoAnterior" style="display: none;">
        <div class="row">
          <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
            <div class="row">
              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <h2><span class="badge badge-pill badge-primary">Tipo: <strong id="tipoTicket"></strong></span></h2>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <h2><span class="badge badge-pill badge-primary">Prioridad: <strong id="prioridadTicket"></strong></span></h2>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <h2><span class="badge badge-pill badge-primary">Estado: <strong id="estadoTicket"></strong></span></h2>
              </div>
            </div>
            <div class="row">
              <div class="input-group col-lg-6 col-md-3 col-sm-6 col-xs-6">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Categoría</span>
                </div>
                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="categoriaTicket" readonly>
              </div>
              <div class="input-group col-lg-6 col-md-3 col-sm-6 col-xs-6">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">SubCategoría</span>
                </div>
                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="subCategoriaTicket" readonly>
              </div>
              <div class="input-group col-lg-6 col-md-3 col-sm-6 col-xs-6">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">Area</span>
                </div>
                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon3" id="areaTicket" readonly>
              </div>
              <div class="col-lg-6 col-md-3 col-sm-6 col-xs-6">                
                <button type="button" class="btn-xs btn btn-round btn-success" id="btnFilesAjuntos">Adjuntos <span class="badge badge-warning" id="totalAdjuntos"></span>
                </button>
                <button type="button" class="btn-xs btn btn-round btn-success" id="btnFilesAjuntosResultados"> Adjuntos Resultados <span class="badge badge-warning" id="totalAdjuntosResultado"></span>
                </button>
              </div>
              
            </div>
            <div class="row">
              <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Título</span>
                </div>
                <input type="text" class="form-control" placeholder="Título" aria-label="Username" aria-describedby="basic-addon1" id="tituloTicket" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" >
                  <div class="card-body" style="height: 235px; overflow-y: scroll;">
                    <div class="row x_title">
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <h5 class="card-title" id="creadoPor"></h5>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"  style="text-align: right;">
                        <h5 id="fechaRegistro"></h5>
                      </div>
                      
                    </div>
                    <!-- <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6> -->
                    <div id="descripcionSoporte"></div>
                    
                  </div>
                </div>
              </div>
            </div>
            <br>
            
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <div class="x_title">
              <h2>Eventos del Ticket</h2>
              
              <div class="clearfix"></div>
            </div>
            <div class="x_content" style="height: 380px; overflow-y: scroll;">
              <ul id="eventosTicket" class="list-unstyled timeline">
                
              </ul>
            </div>
          </div>
        </div>
        <div class="row" id="botonera"></div>
      </div>
      <div class="modal-footer modal-footer-danger">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>