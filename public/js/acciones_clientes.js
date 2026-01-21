// acciones_clientes.js
export function abrirModalDetalleCliente(idPedido) {
    const contenedor = document.getElementById('contenidoModalDetalle');
    const elementoModal = document.getElementById('modalDetallePedido');
    
    if (!elementoModal || !contenedor) {
        console.error("No se encontraron los elementos del modal en el HTML");
        return;
    }

    // 1. Limpiar y mostrar spinner
    contenedor.innerHTML = '<div class="p-5 text-center"><div class="spinner-border text-primary"></div></div>';
    
    // 2. Mostrar el modal de Bootstrap
    const myModal = new bootstrap.Modal(elementoModal);
    myModal.show();

  fetch('index.php?pagina=ver_detalle_cliente&id=' + idPedido)
        .then(response => response.text())
        .then(html => {
            contenedor.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            contenedor.innerHTML = 'Error al cargar.';
        });
}
