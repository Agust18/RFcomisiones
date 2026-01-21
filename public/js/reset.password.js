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

