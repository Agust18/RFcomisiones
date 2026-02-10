export function confirmarCambioDinamico(elemento, id_pedido, estado_anterior) {
    const nuevo_estado = elemento.value;
    if (!nuevo_estado || nuevo_estado === estado_anterior) return;

    const alerta = (typeof Swal !== 'undefined') ? Swal : swal;

    alerta.fire({
        title: '¿Confirmar cambio?',
        icon: 'question',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            // Pasamos AMBOS datos en la URL para que la redirección 301 no los borre
            const url = `index.php?pagina=actualizar_estado&id_pedido=${id_pedido}&nuevo_estado=${encodeURIComponent(nuevo_estado)}`;
            
            return fetch(url, {
                method: 'POST', // Mantenemos POST por semántica
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'include'
            })
            .then(async r => {
                const d = await r.json();
                if (!d.success) throw new Error(d.message);
                return d;
            })
            .catch(e => alerta.showValidationMessage(`Error: ${e.message}`));
        }
    }).then(res => {
        if (res.isConfirmed) {
            elemento.onchange = () => confirmarCambioDinamico(elemento, id_pedido, nuevo_estado);
            alerta.fire({ title: '¡Listo!', icon: 'success', timer: 1000, showConfirmButton: false });
        } else {
            elemento.value = estado_anterior;
        }
    });
}