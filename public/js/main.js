// main.js 
import { verDetallePedido, editarPedido, eliminarPedidoLogico } from "./acciones.js";
//ES LO MÁS IMPORTANTE:
// Sin estas líneas, los botones onclick de tu HTML no funcionan
window.verDetallePedido = verDetallePedido;
window.editarPedido = editarPedido;
window.eliminarPedidoLogico = eliminarPedidoLogico;





import { abrirModalDetalleCliente } from "./acciones_clientes.js";
// Hacemos la función disponible globalmente con el nombre que espera el botón
window.verDetallePedido_cliente = abrirModalDetalleCliente;


import { actualizarEstadoBackground , initFiltrosPedidos} from "./estado-background.js";

import { confirmarCierreSesion } from './logout.js';
window.confirmarCierreSesion = confirmarCierreSesion;

import { confirmarCambioDinamico , } from './pedidos.js';
window.confirmarCambioDinamico = confirmarCambioDinamico;
    
    
import { initValidacionesRegistro } from "./validaciones-registro-login.js";

document.addEventListener("DOMContentLoaded", () => {
    initValidacionesRegistro();
    actualizarEstadoBackground();
    initFiltrosPedidos();
    
});


document.addEventListener('click', function (e) {
    // Caso 1: Botón de ver detalle (Cliente)
    const btnDetalle = e.target.closest('.btn-ver-detalle');
    if (btnDetalle) {
        const id = btnDetalle.getAttribute('data-id');
        abrirModalDetalleCliente(id);
    }

    // Caso 2: Para evitar el error de "confirmarAsignacion"
    // Solo busca el botón de asignación si existe en la página
    const btnAsignar = e.target.closest('.btn-confirmar-asignacion');
    if (btnAsignar) {
        // Aquí llamarías a la función de asignar, pero solo si el botón existe
    }
});