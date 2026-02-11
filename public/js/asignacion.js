// Exportamos la función para que pueda ser utilizada en otros archivos (modularización)
export function confirmarAsignacion(idPedido) {
    
    // Obtenemos el ID del comisionista desde un campo oculto (input hidden) en el HTML.
    // Usamos el operador '?.' (optional chaining) para evitar errores si el elemento no existe.
    const idComisionista = document.getElementById('session_user_id')?.value || '';
    
    // Verificamos si la librería SweetAlert2 (Swal) está disponible; si no, usamos la versión estándar.
    const alerta = (typeof Swal !== 'undefined') ? Swal : swal;

    // Iniciamos la ventana emergente de confirmación
    alerta.fire({
        title: '¿asignarte este pedido?', // Título del mensaje
        text: 'el pedido pasará a tu lista de asignados', // Explicación de la acción
        icon: 'question', // Icono de interrogación
        showCancelButton: true, // Muestra el botón para arrepentirse
        confirmButtonText: 'sí, asignar', // Texto del botón de acción
        cancelButtonText: 'cancelar', // Texto del botón de cierre
        showLoaderOnConfirm: true, // Muestra un círculo de carga mientras se procesa la petición
        
        // Esta función se ejecuta cuando el usuario hace clic en "sí, asignar"
        preConfirm: () => {
            // Define la ruta base del proyecto para evitar errores de rutas relativas
            const base = (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
            
            // Construimos la URL con los parámetros necesarios para que el servidor sepa qué pedido asignar
            const url = `${base}index.php?pagina=asignar_pedido&id_pedido=${idPedido}&nuevo_estado=Asignado&id_comisionista=${idComisionista}`;

            // --- INICIO DE FETCH API ---
            // Enviamos la petición al servidor de forma asíncrona (sin recargar la página)
            return fetch(url, {
                method: 'POST', // Usamos el método POST para indicar que estamos enviando/modificando datos
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest', // Indica al servidor que es una petición AJAX
                    'Content-Type': 'application/json' // Definimos el formato de intercambio de datos
                },
                credentials: 'include' // IMPORTANTE: Envía las cookies para que el servidor sepa quién está logueado
            })
            // Procesamos la respuesta del servidor
            .then(async response => {
                // Leemos el cuerpo de la respuesta como texto plano primero
                const text = await response.text();
                try {
                    // Intentamos convertir ese texto en un objeto JSON de JavaScript
                    const jsonData = JSON.parse(text);
                    
                    // Si el servidor devolvió "success: false", lanzamos un error con el mensaje recibido
                    if (!jsonData.success) throw new Error(jsonData.message || "error desconocido");
                    
                    // Si todo está bien, devolvemos los datos procesados
                    return jsonData;
                } catch (e) {
                    // Si la respuesta no era un JSON válido (por ejemplo, un error de PHP), lo mostramos en consola
                    console.error("respuesta bruta del servidor:", text);
                    throw new Error("el servidor no envio un json valido.");
                }
            })
            // Si algo falla en la conexión o en el proceso anterior, capturamos el error
            .catch(error => {
                // Mostramos el mensaje de error directamente en la ventana de SweetAlert
                alerta.showValidationMessage(`fallo: ${error.message}`);
            });
            // --- FIN DE FETCH API ---
        }
    }).then((result) => {
        // Una vez finalizado el proceso de confirmación y la petición al servidor:
        // Si el usuario confirmó y la respuesta del servidor fue exitosa (success: true)
        if (result.isConfirmed && result.value && result.value.success) {
            // Mostramos una alerta final de éxito
            alerta.fire({
                title: '¡asignado!',
                text: result.value.message,
                icon: 'success',
                timer: 1500, // La alerta se cierra sola después de 1.5 segundos
                showConfirmButton: false
            }).then(() => location.reload()); // Cuando la alerta se cierra, recargamos la página para ver los cambios
        }
    });
}

// Vinculamos la función al objeto global 'window'. 
// Esto es necesario para que los botones que tienen onclick="confirmarAsignacion()" puedan encontrar la función.
window.confirmarAsignacion = confirmarAsignacion;