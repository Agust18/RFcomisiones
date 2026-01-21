export function confirmarCambioDinamico(selectElement, idPedido, estadoAnterior) {
    const nuevoEstado = selectElement.value;
    const fila = selectElement.closest('tr'); // Capturamos la fila antes del Swal
    
    if (!nuevoEstado || nuevoEstado === estadoAnterior) return;

    Swal.fire({
        title: `¿Confirmar ${nuevoEstado}?`,
        text: `Se actualizará el pedido #${idPedido}.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, actualizar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const formData = new FormData();
            formData.append('id_pedido', idPedido);
            formData.append('nuevo_estado', nuevoEstado);

            return fetch('index.php?pagina=actualizar_estado', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(async response => {
                if (!response.ok) throw new Error('Error de red');
                const data = await response.json();
                if (!data.success) throw new Error(data.message || 'Error al actualizar');
                return data;
            })
            .catch(error => {
                Swal.showValidationMessage(`Fallo: ${error.message}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // ACTUALIZACIÓN EN VIVO (Sin reload)
            // 1. Actualizamos el atributo para que los filtros de JS sigan funcionando
            fila.setAttribute('data-estado', nuevoEstado);
            
            // 2. Actualizamos el onchange para la próxima vez
            selectElement.setAttribute('onchange', `confirmarCambioDinamico(this, ${idPedido}, '${nuevoEstado}')`);

            Swal.fire({
                title: '¡Actualizado!',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false
            });

            // OPCIONAL: Si el comisionista solo ve "Pendientes", 
            // podés ocultar la fila si el estado nuevo es "Entregado"
            /*
            if (nuevoEstado === 'Entregado') {
                fila.fadeOut(); // O usar fila.style.display = 'none';
            }
            */
        } else {
            // Revertimos el select si el usuario canceló
            selectElement.value = estadoAnterior;
        }
    });
}

window.confirmarCambioDinamico = confirmarCambioDinamico;


function confirmarAsignacion(idPedido) {
    const idComisionista = document.getElementById('session_user_id').value;

    Swal.fire({
        title: '¿Asignarte este pedido?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, asignar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_pedido', idPedido);
            formData.append('id_comisionista', idComisionista);
            formData.append('nuevo_estado', 'Asignado'); // Valor fijo para asignación

            fetch('index.php?pagina=asignar_pedido', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('¡Asignado!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
}
window.confirmarAsignacion = confirmarAsignacion;