export function initValidacionesRegistro() {
    const formu = document.querySelector("#form-registro");

    if (!formu) return; // Si no hay formulario de registro, salir de la función.
    const nombre = document.getElementById("nombre");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmar = document.getElementById("password_conf");
    const telefono = document.getElementById("telefono");


    if (!nombre || !email || !password || !confirmar || !telefono) return;


    function mostrarError(input, mensaje) {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        input.nextElementSibling.textContent = mensaje;
        input.setCustomValidity(mensaje);
    }

    function mostrarOK(input) {
        input.classList.remove("is-invalid");
        input.classList.add("is-valid");
        input.nextElementSibling.textContent = "";
        input.setCustomValidity("");
    }



    nombre.addEventListener("input", () => {
        //al valor del input lo limpia con trim y verifica su longitud
        if (nombre.value.trim().length < 3) {
            nombre.classList.add("is-invalid");
            nombre.classList.remove("is-valid");
            mostrarError(nombre, "El nombre es demasiado corto");


        } else {
            nombre.classList.remove("is-invalid");
            nombre.classList.add("is-valid");
            mostrarOK(nombre);

        }


    });

    email.addEventListener("input", () => {
        //comprueba formato de email usando regex
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        //Si el correo no coincide con el formato válido → muestra error visual y bloquea el envío del form.
        if (!regex.test(email.value)) {
            email.classList.add("is-invalid");
            email.classList.remove("is-valid");
            mostrarError(email, "El email es inválido");
        } else {
            email.classList.remove("is-invalid");
            email.classList.add("is-valid");
            mostrarOK(email);
        }

    });

    function validarPasswords() {
        if (password.value.length < 8) {
            password.classList.add("is-invalid");
            password.classList.remove("is-valid");
            mostrarError(password, "La contraseña debe tener al menos 8 caracteres.");
        } else {
            password.classList.remove("is-invalid");
            password.classList.add("is-valid");
            mostrarOK(password);
        }

        if (password.value !== confirmar.value) {
            confirmar.classList.add("is-invalid");
            confirmar.classList.remove("is-valid");
            mostrarError(confirmar, "Las contraseñas no coinciden.");
        } else {
            confirmar.classList.remove("is-invalid");
            confirmar.classList.add("is-valid");
            mostrarOK(confirmar);
        }

    }

    password.addEventListener("input", validarPasswords);
    confirmar.addEventListener("input", validarPasswords);


    telefono.addEventListener("input", () => {
        const regex = /^\d{10}$/; //solo números y exactamente 10 dígitos
        if (!regex.test(telefono.value)) {
            telefono.classList.add("is-invalid");
            telefono.classList.remove("is-valid");
            mostrarError(telefono, "El telefono debe tener 10 dígitos numéricos.");
        } else {
            telefono.classList.remove("is-invalid");
            telefono.classList.add("is-valid");
            mostrarOK(telefono);
        }

    });

    const form = document.querySelector("form");

    formu.addEventListener("submit", () => {
        const btn = formu.querySelector("button[type='submit']");
        if (btn) btn.disabled = true;
    });


    form.addEventListener("submit", (e) => {
        validarPasswords();
        if (!form.checkValidity()) {
            e.preventDefault();
            form.classList.add("was-validated");
        }
    });






}

