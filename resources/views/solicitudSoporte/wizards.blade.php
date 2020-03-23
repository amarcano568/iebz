<div id="wizard" class="form_wizard wizard_horizontal">
  <ul class="wizard_steps">
    <li>
      <a href="#step-1">
        <span class="step_no">1</span>
        <span class="step_descr">
          Paso 1<br />
          <small>Canal de Solicitud</small>
        </span>
      </a>
    </li>
    <li>
      <a href="#step-2">
        <span class="step_no">2</span>
        <span class="step_descr">
          Paso 2<br />
          <small>Descripción del problema</small>
        </span>
      </a>
    </li>
    <li>
      <a href="#step-3">
        <span class="step_no">3</span>
        <span class="step_descr">
          Paso 3<br />
          <small>Adjuntar archivos</small>
        </span>
      </a>
    </li>
    <li>
      <a href="#step-4">
        <span class="step_no">4</span>
        <span class="step_descr">
          Paso 4<br />
          <small>Enviar Ticket</small>
        </span>
      </a>
    </li>
  </ul>
  <div id="step-1">
    @include('solicitudSoporte.paso1')
  </div>
  <div id="step-2">
    @include('solicitudSoporte.paso2')
  </div>
  <div id="step-3">
    @include('solicitudSoporte.paso3')
  </div>
  <div id="step-4">
    @include('solicitudSoporte.paso4')
  </div>
</div>