


export function confirmarCierreSesion(event) {
    // 1. ESTO ES VITAL: Detiene el comportamiento del <a>
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    Swal.fire({
        title: 'Cerrar sesión',
        text: "¿Estás seguro de que deseas cerrar sesión?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, salir',
        showLoaderOnConfirm: true,
        preConfirm: () => {
    // Usamos la ruta amigable /logout en lugar de index.php?pagina=logout
    const url = (typeof BASE_URL !== 'undefined') ? BASE_URL + 'logout' : 'logout';
    
    return fetch(url, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
            .then(response => response.text())
            .then(data => {
                // Intentamos encontrar el JSON incluso si el index.php manda HTML
                const inicio = data.indexOf('{');
                const fin = data.lastIndexOf('}') + 1;
                if (inicio === -1) throw new Error("Respuesta inválida del servidor");
                return JSON.parse(data.substring(inicio, fin));
            })
            .catch(error => {
                Swal.showValidationMessage(`Error: ${error.message}`);
            });
        }
    }).then((result) => {
    if (result.isConfirmed && result.value?.success) {
    // Forzamos la ruta completa para evitar que salte a la raíz del servidor
    window.location.href = 'http://localhost/RFcomisiones/login';
}});
}