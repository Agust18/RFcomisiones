export function verDetallePedido(id) {
    const contentDiv = document.getElementById('modal-body-content');
    contentDiv.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>';
    
    const modal = new bootstrap.Modal(document.getElementById('modalPedidos'));
    modal.show();

    fetch(`index.php?pagina=detalle_pedido&id=${id}`)
        .then(res => res.text())
        .then(html => contentDiv.innerHTML = html)
        .catch(err => contentDiv.innerHTML = '<div class="p-4 text-danger">Error al cargar detalle.</div>');
}

export function editarPedido(id) {
    const contentDiv = document.getElementById('contenidoEditar');
    contentDiv.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-warning"></div></div>';
    
    const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
    modal.show();

    fetch(`index.php?pagina=form_editar_pedido&id=${id}`)
        .then(res => res.text())
        .then(html => contentDiv.innerHTML = html)
        .catch(err => contentDiv.innerHTML = '<div class="p-4 text-danger">Error al cargar formulario.</div>');
}



// 3. ELIMINADO LÓGICO (TACHO)
export function eliminarPedidoLogico(id) {
    Swal.fire({
        title: '¿Mover a la papelera?',
        text: "El pedido no se borrará, pero dejará de aparecer en tu lista activa.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_pedido', id);

            fetch('index.php?pagina=eliminar_pedido_logico', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminado', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}



document.addEventListener('submit', function(e) {
    if (e.target && e.target.id === 'formActualizarPedido') {
        e.preventDefault();
        
        // Bloquear el botón para evitar múltiples clics
        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

        const formData = new FormData(e.target);

        fetch('index.php?pagina=procesar_edicion_action', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Actualizado!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = 'Guardar Cambios';
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Hubo un fallo en la conexión', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Guardar Cambios';
        });
    }
});

// IMPORTANTE: Exponer funciones al ámbito global (window) 
// para que el 'onclick' del HTML pueda encontrarlas
window.verDetallePedido = verDetallePedido;
window.editarPedido = editarPedido;
window.eliminarPedidoLogico = eliminarPedidoLogico;