/**
 * Usamos la BASE_URL de tu PHP. 
 * IMPORTANTE: No usamos 'index.php' en ninguna URL de fetch.
 */
const getUrl = (path) => {
    const base = BASE_URL.endsWith('/') ? BASE_URL : `${BASE_URL}/`;
    return `${base}${path}`;
};

// 1. VER DETALLE
export function verDetallePedido(id) {
    const contentDiv = document.getElementById('modal-body-content');
    const modalElement = document.getElementById('modalPedidos');
    
    if (!contentDiv || !modalElement) return console.error("Faltan contenedores de Detalle");

    const modal = new bootstrap.Modal(modalElement);
    contentDiv.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>';
    modal.show();

    // Ruta limpia: detalle_pedido?id=X
    fetch(getUrl(`detalle_pedido?id=${id}`))
        .then(res => res.text())
        .then(html => contentDiv.innerHTML = html)
        .catch(err => console.error("Error cargando detalle:", err));
}

// 2. ABRIR FORMULARIO EDITAR
export function editarPedido(id) {
    const contentDiv = document.getElementById('contenidoEditar');
    const modalElement = document.getElementById('modalEditar');
    
    if (!contentDiv || !modalElement) return console.error("Faltan contenedores de Edición");

    const modal = new bootstrap.Modal(modalElement);
    contentDiv.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-warning"></div></div>';
    modal.show();

    // Ruta limpia: form_editar_pedido?id=X
    fetch(getUrl(`form_editar_pedido?id=${id}`)) 
        .then(res => res.text())
        .then(html => contentDiv.innerHTML = html)
        .catch(err => console.error("Error cargando formulario:", err));
}

// 3. ELIMINADO LÓGICO (POST)
export function eliminarPedidoLogico(id) {
    Swal.fire({
        title: '¿Mover a la papelera?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_pedido', id);

            // POST a ruta limpia
            fetch(getUrl('eliminar_pedido_logico'), {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminado', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}

// 4. GUARDAR CAMBIOS (SUBMIT DEL FORMULARIO)
document.addEventListener('submit', function(e) {
    if (e.target && e.target.id === 'formActualizarPedido') {
        e.preventDefault();
        
        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = 'Guardando...';

        const formData = new FormData(e.target);

        // AQUÍ ESTÁ LA MAGIA: 
        // Llamamos a 'procesar_edicion_action'. 
        // El .htaccess lo traduce internamente a index.php?pagina=procesar_edicion_action
        // MANTENIENDO EL POST ORIGINAL.
        fetch(getUrl('procesar_edicion_action'), {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('¡Éxito!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = 'Guardar Cambios';
            }
        })
        .catch(err => {
            console.error("Error crítico en el POST:", err);
            btn.disabled = false;
            btn.innerHTML = 'Guardar Cambios';
        });
    }
});

// HACEMOS LAS FUNCIONES GLOBALES
window.verDetallePedido = verDetallePedido;
window.editarPedido = editarPedido;
window.eliminarPedidoLogico = eliminarPedidoLogico;