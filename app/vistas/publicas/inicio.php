<section class="container py-4 my-md-4">
  <div class="row align-items-center g-4 g-lg-5">
    <div class="col-lg-6">
      <h2 class="fw-semibold text-dark mb-3" style="font-size: 2.2rem; letter-spacing: -0.5px;">
        Optimiza tus paquetes con <span class="text-primary">inteligencia.</span>
      </h2>
      <p class="text-secondary mb-4" style="font-size: 1rem; line-height: 1.6;">
        La forma más eficiente de registrar, gestionar y asegurar tus pedidos en un solo lugar.
      </p>
      <div class="d-flex flex-wrap gap-3">
        <a href="<?php echo BASE_URL ?>registro" class="btn btn-primary rounded-pill px-4 py-2 shadow-none"
          style="font-size: 0.9rem; font-weight: 500;">
          Empezar gratis
        </a>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="position-relative text-center">
        <img src="./app/vistas/img/seguro.webp" alt="Gestión" class="img-fluid rounded-3 border border-light shadow-sm"
          style="max-height: 380px;">

      </div>
    </div>
  </div>
</section>

<section class="bg-white py-4 border-top border-bottom">
  <div class="container">
    <div class="row g-0">
      <div class="col-md-4">
        <div class="p-3 h-100">
          <div class="text-primary mb-2">
            <i class="bi bi-pencil-square" style="font-size: 1.25rem;"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-2">Registro Ágil</h6>
          <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.5;">
            Carga tus pedidos en segundos indicando los puntos de retiro y entrega.
          </p>
        </div>
      </div>

      <div class="col-md-4 border-start-md" style="border-left: 1px solid #f0f0f0;">
        <div class="p-3 h-100">
          <div class="text-primary mb-2">
            <i class="bi bi-funnel" style="font-size: 1.25rem;"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-2">Control Inteligente</h6>
          <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.5;">
            Visualiza tu historial con filtros por estado: Asignado, Recogido o Entregado.
          </p>
        </div>
      </div>

      <div class="col-md-4 border-start-md" style="border-left: 1px solid #f0f0f0;">
        <div class="p-3 h-100">
          <div class="text-primary mb-2">
            <i class="bi bi-whatsapp" style="font-size: 1.25rem;"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-2">Conexión Directa</h6>
          <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.5;">
            Acceso inmediato al WhatsApp del comisionista asignado para coordinar detalles.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>


<section class="py-5 mt-3 bg-white" id="preguntas-frecuentes">
  <div class="container">
    <div class="text-center mb-4">
      <h3 class="fw-semibold text-dark">Preguntas frecuentes</h3>
      <p class="text-muted small">Información clave sobre el uso de RFcomisiones.</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="accordion accordion-flush" id="faqAccordion">

          <div class="accordion-item border-bottom bg-transparent">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed bg-transparent fw-medium text-dark py-3 px-0 shadow-none"
                style="font-size: 0.95rem;" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                <i class="bi bi-person-plus me-2 text-primary" style="font-size: 0.9rem;"></i>
                ¿Cómo empiezo a enviar?
              </button>
            </h2>
            <div id="q1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body px-0 pb-3 pt-1">
                <p class="mb-0 text-secondary small lh-base" style="border-left: 2px solid #eee; padding-left: 1rem;">
                  Solo necesitás registrarte. Una vez dentro, usá el botón "Nuevo Pedido", cargá los datos y aparecerá automáticamente en tu historial de seguimiento.
                </p>
              </div>
            </div>
          </div>

          <div class="accordion-item border-bottom bg-transparent">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed bg-transparent fw-medium text-dark py-3 px-0 shadow-none"
                style="font-size: 0.95rem;" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                <i class="bi bi-geo-alt me-2 text-primary" style="font-size: 0.9rem;"></i>
                ¿Cómo sé el estado de mi pedido?
              </button>
            </h2>
            <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body px-0 pb-3 pt-1">
                <p class="mb-0 text-secondary small lh-base" style="border-left: 2px solid #eee; padding-left: 1rem;">
                  En tu panel tenés botones de acceso rápido para filtrar pedidos: <strong>Asignados, Recogidos, En Camino o Entregados</strong>. Así sabés siempre dónde está tu paquete.
                </p>
              </div>
            </div>
          </div>

          <div class="accordion-item border-bottom bg-transparent">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed bg-transparent fw-medium text-dark py-3 px-0 shadow-none"
                style="font-size: 0.95rem;" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                <i class="bi bi-x-circle me-2 text-primary" style="font-size: 0.9rem;"></i>
                ¿Puedo cancelar un pedido?
              </button>
            </h2>
            <div id="q3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body px-0 pb-3 pt-1">
                <p class="mb-0 text-secondary small lh-base" style="border-left: 2px solid #eee; padding-left: 1rem;">
                  Sí, pero debe informarse vía WhatsApp con <strong>48hs de antelación</strong> indicando el número de seguimiento. Si se cancela al momento del retiro, se cobrará una comisión de retorno.
                </p>
              </div>
            </div>
          </div>

          <div class="accordion-item border-bottom bg-transparent">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed bg-transparent fw-medium text-dark py-3 px-0 shadow-none"
                style="font-size: 0.95rem;" type="button" data-bs-toggle="collapse" data-bs-target="#q4">
                <i class="bi bi-chat-dots me-2 text-primary" style="font-size: 0.9rem;"></i>
                ¿Puedo hablar con quien lleva mi paquete?
              </button>
            </h2>
            <div id="q4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body px-0 pb-3 pt-1">
                <p class="mb-0 text-secondary small lh-base" style="border-left: 2px solid #eee; padding-left: 1rem;">
                  ¡Sí! Al abrir el detalle de tu pedido, encontrarás un botón directo para iniciar un chat de WhatsApp con el comisionista asignado a tu código.
                </p>
              </div>
            </div>
          </div>

          <div class="accordion-item border-0 bg-transparent">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed bg-transparent fw-medium text-dark py-3 px-0 shadow-none"
                style="font-size: 0.95rem;" type="button" data-bs-toggle="collapse" data-bs-target="#q5">
                <i class="bi bi-shield-check me-2 text-primary" style="font-size: 0.9rem;"></i>
                ¿Qué pasa si el pedido no está listo?
              </button>
            </h2>
            <div id="q5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body px-0 pb-3 pt-1">
                <p class="mb-0 text-secondary small lh-base" style="border-left: 2px solid #eee; padding-left: 1rem;">
                  El pedido debe estar embalado al llegar el comisionista. Hay una tolerancia de 10-15 minutos; de lo contrario, se reprograma para el próximo viaje o se aplica un recargo.
                </p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>