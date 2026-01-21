<section class="container my-5 py-lg-4">
  <div class="row g-0 shadow-sm rounded-4 overflow-hidden border">
    <div class="col-md-6 d-none d-md-block">
      <img src="./app/vistas/img/RFEnvios.webp"
           alt="Login ilustración" 
           class="img-fluid h-100 w-100 object-fit-cover"
           style="min-height: 500px; filter: brightness(0.95);">
    </div>

    <div class="col-12 col-md-6 p-4 p-lg-5 d-flex flex-column justify-content-center bg-white">
      <div class="text-center mb-4">
        <h6 class="text-primary fw-bold text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 2px;">RFcomisiones</h6>
        <h3 class="fw-semibold text-dark" style="letter-spacing: -0.5px;">Bienvenido de nuevo</h3>
        <p class="text-muted small">Ingresa tus credenciales para acceder.</p>
      </div>

      <form action="<?php echo BASE_URL; ?>login_action" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label small fw-semibold text-secondary">Correo Electrónico</label>
          <input type="email" 
                 class="form-control form-control-sm py-2 px-3 bg-light border-0" 
                 name="email" id="email" 
                 placeholder="nombre@ejemplo.com"
                 style="font-size: 0.9rem;">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label small fw-semibold text-secondary">Contraseña</label>
          <input type="password" 
                 name="contraseña" 
                 class="form-control form-control-sm py-2 px-3 bg-light border-0" 
                 id="contraseña" 
                 placeholder="••••••••"
                 style="font-size: 0.9rem;">
        </div>

       <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
  <div class="form-check m-0">
    <!-- <input class="form-check-input shadow-none" type="checkbox" id="rememberMe" style="cursor: pointer;"> -->
    <!-- <label class="form-check-label text-muted" for="rememberMe" style="font-size: 0.85rem; cursor: pointer;">
      Recordarme
    </label> -->
  </div>
  
  <a href="<?php echo BASE_URL?>solicitar_reset" class="text-decoration-none text-primary fw-medium" style="font-size: 0.8rem;">
    ¿Olvidaste tu contraseña?
  </a>
</div> 

        <button type="submit" 
                class="btn btn-primary w-100 py-2 shadow-none border-0 rounded-3" 
                style="font-size: 0.9rem; font-weight: 600; letter-spacing: 0.3px;">
          Iniciar Sesión
        </button>
      </form>

      <p class="text-center mt-4 mb-0 text-muted small">
        ¿No tienes una cuenta?
        <a href="<?php echo BASE_URL; ?>registro" class="text-decoration-none text-primary fw-bold ms-1">Regístrate</a>
      </p>
    </div>
  </div>
</section>