export function actualizarEstadoBackground() {
    document.querySelectorAll(".estado-select").forEach(select => {

        const limpiarEstados = () => {
            select.classList.remove(
                "estado-Asignado",
                "estado-Recogido",
                "estado-EnCamino",
                "estado-Entregado"
            );
        };

        const aplicarColor = () => {
            limpiarEstados();
            select.classList.add(`estado-${select.value}`);
        };

        // Al cargar
        aplicarColor();

        // Cuando cambia
        select.addEventListener("change", aplicarColor);

        //  Cuando el usuario ABRE el select
        select.addEventListener("mousedown", limpiarEstados);

        //  Cuando pierde foco (se cierra)
        select.addEventListener("blur", aplicarColor);
    });
}

// Esta función maneja el buscador en tiempo real sobre lo que hay en pantalla
export function initFiltrosPedidos() {
    const inputBusqueda = document.getElementById('filtro-cliente');
    
    if (inputBusqueda) {
        // 1. Filtrado en tiempo real (Visual)
        inputBusqueda.addEventListener('input', function() {
            const texto = this.value.toLowerCase().trim();
            const filas = document.querySelectorAll('#tabla-pedidos-body tr');

            filas.forEach(fila => {
                const nombre = (fila.getAttribute('data-cliente') || "").toLowerCase();
                const codigo = (fila.getAttribute('data-codigo') || "").toLowerCase();
                fila.style.display = (nombre.includes(texto) || codigo.includes(texto)) ? '' : 'none';
            });
        });

        // 2. Escuchar la tecla ENTER para búsqueda global
        inputBusqueda.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                window.enviarFiltroManual(e);
            }
        });
    }
}

// Redirección para botones de estado
window.filtrarPorEstado = function(valor) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('pagina', 'comisionista'); // Aseguramos que se mantenga en la vista
    urlParams.set('estado', valor);
    urlParams.set('q', ''); // Limpiamos búsqueda al cambiar de pestaña
    urlParams.set('p', '1');
    window.location.href = "?" + urlParams.toString();
}

// Redirección para búsqueda con Enter
window.enviarFiltroManual = function(e) {
    if(e) e.preventDefault();
    const q = document.getElementById('filtro-cliente').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('pagina', 'comisionista');
    urlParams.set('q', q);
    urlParams.set('estado', 'Todos'); // Al buscar texto, solemos buscar en todos los estados
    urlParams.set('p', '1');
    window.location.href = "?" + urlParams.toString();
}