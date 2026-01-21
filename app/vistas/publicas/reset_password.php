<?php
// Capturamos el token que viene en la URL limpia
$token = $_GET['token'] ?? '';
?>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 15px;">
        <h3 class="text-center mb-4">Nueva Contraseña</h3>
        <p class="text-muted text-center small">Ingresa una clave segura de al menos 8 caracteres.</p>

        <form action="confirmar_reset" method="POST" id="resetForm">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div class="mb-3">
                <label class="form-label small fw-bold">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                
                <div class="progress mt-2" style="height: 5px;">
                    <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <div id="strengthText" class="small mt-1 fw-bold"></div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Confirmar Contraseña</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="••••••••" required>
                <div id="matchText" class="small mt-1"></div>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary w-100 fw-bold mt-2" disabled>
                Actualizar Contraseña
            </button>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const password = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const matchText = document.getElementById('matchText');
    const submitBtn = document.getElementById('submitBtn');

    const updateStrength = () => {
        const val = password.value;
        let strength = 0;

        if (val.length === 0) strength = 0;
        else {
            if (val.length >= 8) strength += 25;
            if (val.match(/[a-z]/) && val.match(/[A-Z]/)) strength += 25;
            if (val.match(/\d/)) strength += 25;
            if (val.match(/[^a-zA-Z\d]/)) strength += 25;
        }

        strengthBar.style.width = strength + '%';
        
        // Colores y textos según fuerza
        const levels = [
            { class: 'bg-secondary', text: '' },
            { class: 'bg-danger', text: 'Muy débil' },
            { class: 'bg-warning', text: 'Débil' },
            { class: 'bg-info', text: 'Buena' },
            { class: 'bg-success', text: 'Muy fuerte' }
        ];

        const level = strength / 25;
        strengthBar.className = `progress-bar ${levels[level].class}`;
        strengthText.innerText = levels[level].text;
        strengthText.className = `small mt-1 fw-bold text-${levels[level].class.replace('bg-', '')}`;
        
        validateForm();
    };

    const validateForm = () => {
        const p1 = password.value;
        const p2 = confirm.value;
        
        // Validar coincidencia y longitud mínima
        const isMatch = p1 === p2 && p1.length >= 8;
        const hasContent = p2.length > 0;

        if (hasContent) {
            matchText.innerText = isMatch ? "✓ Las contraseñas coinciden" : "✗ Las contraseñas no coinciden";
            matchText.className = `small mt-1 ${isMatch ? "text-success" : "text-danger"}`;
        } else {
            matchText.innerText = "";
        }

        // Habilitar botón solo si todo es correcto
        submitBtn.disabled = !isMatch;
    };

    password.addEventListener('input', updateStrength);
    confirm.addEventListener('input', validateForm);
});
</script>