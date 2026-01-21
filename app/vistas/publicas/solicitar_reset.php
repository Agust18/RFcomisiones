<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card border-0 shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 20px;">
        <h4 class="text-center fw-bold mb-1">Recuperar Clave</h4>
        <p class="text-muted text-center small mb-4">Te enviaremos un enlace para restablecer tu cuenta.</p>

        <form action="<?= BASE_URL ?>procesar_recuperacion" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Tu Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i
                            class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 bg-light"
                        placeholder="nombre@ejemplo.com" required>
                </div>
            </div>

            <button type="submit" id="btnEnviar" class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-pill mt-2">
                Enviar enlace de recuperación
            </button>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>login" class="small text-decoration-none fw-bold text-muted">
                    <i class="bi bi-arrow-left"></i> Volver al Inicio de Sesión
                </a>
            </div>
        </form>
    </div>
</div>
<script>
// Esto evita que el usuario haga click muchas veces mientras se envía el mail
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('btnEnviar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
});
</script>