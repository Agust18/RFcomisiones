<nav class="navbar navbar-expand-lg bg-white border-bottom py-3 shadow-none">
  <div class="container">
    <a class="navbar-brand fw-semibold text-dark d-flex align-items-center" 
       href="<?php echo BASE_URL; ?>inicio" 
       style="font-size: 1.05rem; letter-spacing: -0.5px;">
      <img src="<?php echo BASE_URL; ?>app/vistas/img/icono.webp" 
           alt="icono RFcomisiones" 
           class="me-2 rounded-2" 
           width="30" 
           height="30"
           style="object-fit: cover;">
      <span>RFcomisiones</span>
    </a>

    <button class="navbar-toggler border-0 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon" style="width: 1.1rem; height: 1.1rem;"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <div class="d-flex flex-column flex-lg-row align-items-center gap-3 gap-lg-5 mt-3 mt-lg-0 pb-3 pb-lg-0">
        
        <a class="text-secondary text-decoration-none fw-medium py-1" 
           href="<?php echo BASE_URL; ?>como-funciona" 
           style="font-size: 0.85rem; white-space: nowrap;">¿Cómo funciona?</a>
           
        <a class="text-dark text-decoration-none fw-medium py-1" 
           href="<?php echo BASE_URL; ?>login" 
           style="font-size: 0.85rem; white-space: nowrap;">Iniciar sesión</a>
        
        <a class="btn btn-primary rounded-pill px-4" 
           href="<?php echo BASE_URL; ?>registro" 
           role="button" 
           style="font-size: 0.85rem; padding-top: 0.5rem; padding-bottom: 0.5rem; font-weight: 500; white-space: nowrap;">
          Registrarse
        </a>
      </div>
    </div>
  </div>
</nav>