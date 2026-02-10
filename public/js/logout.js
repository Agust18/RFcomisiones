export function confirmarCierreSesion(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    Swal.fire({
        title: 'Cerrar sesión',
        text: "¿Estás seguro de que deseas cerrar sesión?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, salir',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            // Usamos BASE_URL global. Si no existe, usamos la raíz '/'
            const urlBase = (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
            const urlFetch = urlBase + 'logout';
            
            return fetch(urlFetch, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) throw new Error("Error en la respuesta del servidor");
                return response.text();
            })
            .then(data => {
                // Limpieza de JSON (por si hay espacios o basura en el buffer de PHP)
                const inicio = data.indexOf('{');
                const fin = data.lastIndexOf('}') + 1;
                if (inicio === -1) throw new Error("Respuesta inválida del servidor");
                return JSON.parse(data.substring(inicio, fin));
            })
            .catch(error => {
                Swal.showValidationMessage(`Error: ${error.message}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value?.success) {
            // REDIRECCIÓN DINÁMICA:
            // Usamos BASE_URL para ir a 'login'. Así evitas el localhost fijo.
            const urlBase = (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
            window.location.href = urlBase + 'login';
        }
    });
}