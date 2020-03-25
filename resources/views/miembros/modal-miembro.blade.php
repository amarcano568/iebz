  <div id="ModalAgregarMiembro" class="modal fade" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-info" id="TituloModalReferencia"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>          
        </div>
        <div class="modal-body" >


            <div class="x_content">

                    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                      <li class="nav-item" id="tab-home">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Datos Personales </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-users"></i> Grupo Familiar</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><i class="fas fa-paperclip"></i> Documentos</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="ministerios-tab" data-toggle="tab" href="#tabMinisterios" role="tab" aria-controls="contact" aria-selected="false"><i class="fas fa-project-diagram"></i> Ministerios</a>
                      </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        @include('miembros.datos-personales')
                      </div>
                      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        @include('miembros.grupo-familiar')

                      </div>
                      <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        @include('miembros.documentos-adjuntos')

                      </div>
                      <div class="tab-pane fade" id="tabMinisterios" role="tabpanel" aria-labelledby="contact-tab">
                        @include('miembros.mis-ministerios')

                      </div>
                    </div>
                  </div>

          
        </div>
        <div class="modal-footer modal-footer-danger">
          
        </div>
      </div>
    </div>
  </div>
