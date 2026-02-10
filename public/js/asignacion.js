// agregamos export al inicio para que main.js pueda importarlo sin errores
export function confirmarAsignacion(idPedido) {
    // buscamos el id del usuario. si no existe el input, usamos un valor vacio
    const idComisionista = document.getElementById('session_user_id')?.value || '';
    
    const alerta = (typeof Swal !== 'undefined') ? Swal : swal;

    alerta.fire({
        title: '¿asignarte este pedido?',
        text: 'el pedido pasará a tu lista de asignados',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'sí, asignar',
        cancelButtonText: 'cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const base = (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
            
            // enviamos los datos por url (query params) como pide tu logica actual
            const url = `${base}index.php?pagina=asignar_pedido&id_pedido=${idPedido}&nuevo_estado=Asignado&id_comisionista=${idComisionista}`;

            return fetch(url, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json' 
                },
                credentials: 'include' // indispensable para mantener la sesion activa
            })
            .then(async response => {
                const text = await response.text();
                try {
                    // intentamos convertir la respuesta a objeto javascript
                    const jsonData = JSON.parse(text);
                    if (!jsonData.success) throw new Error(jsonData.message || "error desconocido");
                    return jsonData;
                } catch (e) {
                    console.error("respuesta bruta del servidor:", text);
                    throw new Error("el servidor no envio un json valido.");
                }
            })
            .catch(error => {
                alerta.showValidationMessage(`fallo: ${error.message}`);
            });
        }
    }).then((result) => {
        // si el servidor confirmo el exito, recargamos la pagina
        if (result.isConfirmed && result.value && result.value.success) {
            alerta.fire({
                title: '¡asignado!',
                text: result.value.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        }
    });
}

// esto permite que el onclick="confirmarAsignacion()" del html funcione
window.confirmarAsignacion = confirmarAsignacion;