
<div class="text-center mb-4">
  <h6 class="text-primary fw-bold text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 2px;">RFcomisiones</h6>
  <h3 class="fw-semibold text-dark" style="letter-spacing: -0.5px;">Crea tu cuenta</h3>
  <p class="text-muted small">Únete y gestiona tus pedidos fácilmente.</p>
</div>

<div class="card shadow-none border rounded-4 p-4 p-lg-5 m-auto bg-white" style="max-width: 450px; width: 100%;">
  <form action="<?php echo BASE_URL; ?>registro_action" id="form-registro" method="POST">
    
    <div class="mb-3">
      <label for="nombre" class="form-label small fw-semibold text-secondary">Nombre completo</label>
      <input type="text" name="nombre" id="nombre" 
             class="form-control bg-light border-0 py-2 px-3" 
             style="font-size: 0.9rem;" placeholder="Nombre" required>
    </div>

    <div class="mb-3">
      <label for="correo" class="form-label small fw-semibold text-secondary">Correo electrónico</label>
      <input type="email" name="email" id="email" 
             class="form-control bg-light border-0 py-2 px-3" 
             style="font-size: 0.9rem;" placeholder="tu@correo.com" required>
    </div>

    <div class="mb-3">
      <label for="telefono" class="form-label small fw-semibold text-secondary">Teléfono</label>
      <input type="tel" id="telefono" name="telefono" 
             class="form-control bg-light border-0 py-2 px-3" 
             style="font-size: 0.9rem;" pattern="\d{10}" placeholder="Telefono" required>
    </div>

    <div class="row g-2">
      <div class="col-md-6 mb-3">
        <label for="password" class="form-label small fw-semibold text-secondary">Contraseña</label>
        <input type="password" id="password" name="contraseña" 
               class="form-control bg-light border-0 py-2 px-3" 
               style="font-size: 0.9rem;" placeholder="Contraseña" required>
      </div>
      <div class="col-md-6 mb-3">
        <label for="password_conf" class="form-label small fw-semibold text-secondary">Confirmar</label>
        <input type="password" id="password_conf" name="contraseña_conf" 
               class="form-control bg-light border-0 py-2 px-3" 
               style="font-size: 0.9rem;" placeholder="Confirmar contraseña" required>
      </div>
    </div>

    <div class="d-grid mt-4">
      <button type="submit" class="btn btn-primary rounded-3 py-2 border-0 shadow-none" 
              style="font-size: 0.9rem; font-weight: 600;">
        Registrarse ahora
      </button>
    </div>
  </form>

  <div class="text-center mt-4">
    <p class="mb-0 text-muted small">
      ¿Ya tienes una cuenta?
      <a href="<?php echo BASE_URL; ?>login" class="text-primary text-decoration-none fw-bold ms-1">Inicia sesión</a>
    </p>
  </div>
</div>