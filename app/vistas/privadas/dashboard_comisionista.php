<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

verificar_acceso('comisionista');
require_once __DIR__ . '/../../funciones/utilidad.php';
require_once __DIR__ . '/../../funciones/funciones.php'; // Usa el nombre real de tu archivo de funciones (probablemente 'funciones.php')
require_once __DIR__ . '/../../acciones/paginador.php'; // Incluye el paginador

// Obtener ID del comisionista logueado
$id_comisionista_logeado = $_SESSION['id_usuario'] ?? 0;
$rol_usuario = $_SESSION['rol'] ?? 'invitado';

// Estados activos que un comisionista debe ver en su tabla principal (Mis Pedidos)
// $estados_activos_comisionista = ['Asignado', 'Recogido', 'EnCamino', 'Entregado'];

$pedidos_pendientes = obtener_pedidos_comisionistas('Pendiente', null) ?: [];
// $pedidos_en_proceso = obtener_pedidos_comisionistas($estados_activos_comisionista, $id_comisionista_logeado) ?: [];
//Obtener el historial de pedidos liquidados/cerrados
// $pedidos_cerrados = obtener_pedidos_cerrados($id_comisionista_logeado);

$total_asignados = contar_pedidos_por_estado($id_comisionista_logeado, 'Asignado');
$total_entregados = contar_pedidos_por_estado($id_comisionista_logeado, 'Entregado');
$total_completados = contar_pedidos_por_estado($id_comisionista_logeado, 'Completado');
?>
<nav class="navbar navbar-expand-lg bg-white border-bottom py-2 shadow-sm">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand fs-6" href="#">
            <span class="text-muted fw-light">Hola,</span>
            <span
                class="fw-bold text-dark"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Comisionista'); ?></span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarDashboard">
            <span class="navbar-toggler-icon" style="width: 1.1em;"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarDashboard">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3 ms-auto mt-3 mt-lg-0">
                <?php if ($rol_usuario === 'administrador'): ?>
                    <!-- <a href="<?= BASE_URL; ?>administracion" class="btn btn-light btn-sm rounded-pill px-3">Panel Admin</a> -->
                <?php endif; ?>
                <a href="#" onclick="confirmarCierreSesion(event)"
                    class="btn btn-outline-danger btn-sm rounded-pill px-3">
                    <i class="fas fa-sign-out-alt me-1"></i> Salir
                </a>
            </div>
        </div>
    </div>
</nav>
<?php
if (isset($_GET['res'])) {
    $mensajes = [
        'ok' => ['clase' => '#198754', 'texto' => 'Actualizado', 'icono' => 'check'],
        'error_db' => ['clase' => '#dc3545', 'texto' => 'Error de red', 'icono' => 'times'],
        'datos_invalidos' => ['clase' => '#ffc107', 'texto' => 'Datos nulos', 'icono' => 'info']
    ];

    if (array_key_exists($_GET['res'], $mensajes)) {
        $m = $mensajes[$_GET['res']];
        ?>
        <div id="mini-notificacion"
            style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: white; padding: 12px 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-left: 5px solid <?= $m['clase'] ?>; display: flex; align-items: center; gap: 10px; animation: slideIn 0.5s ease-out;">
            <i class="fas fa-<?= $m['icono'] ?>" style="color: <?= $m['clase'] ?>;"></i>
            <span style="font-size: 14px; font-weight: 500; color: #333;"><?= $m['texto'] ?></span>
        </div>
        <?php
    }
}
?>
<div class="row g-4">
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-primary">Mi Trabajo</h6>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Asignados</span>
                        <span class="badge bg-warning text-dark rounded-pill px-3"><?= $total_asignados; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Entregados</span>
                        <span class="badge bg-success rounded-pill px-3"><?= $total_entregados; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-success bg-opacity-10 border-0 py-3">
                <h6 class="mb-0 fw-bold text-success"><i class="fas fa-layer-group me-2"></i>Cola de Espera</h6>
            </div>
            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                <?php if (empty($pedidos_pendientes)): ?>
                    <div class="p-4 text-center text-muted small">No hay pedidos pendientes</div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($pedidos_pendientes as $pedido): ?>
                            <div class="list-group-item border-0 border-bottom p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="small">
                                        <span class="fw-bold text-dark">#<?= $pedido['codigo_seguimiento']; ?></span><br>
                                        <span class="text-muted"><?= htmlspecialchars($pedido['nombre_cliente']); ?></span>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="button" onclick="confirmarAsignacion(<?= $pedido['id']; ?>)"
                                            class="btn btn-success btn-sm rounded-pill px-3">
                                            Asignar
                                        </button>

                                        <button type="button" class="btn btn-light btn-sm text-info rounded-circle shadow-sm"
                                            onclick="verDetallePedido(<?= $pedido['id']; ?>)"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div><input type="hidden" id="session_user_id" value="<?= $_SESSION['id_usuario']; ?>">
    </div>

    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="row align-items-center g-4 py-2">
                <div class="col-12 col-md-5 col-lg-4">
                    <form id="searchForm" onsubmit="enviarFiltroManual(event)">
                        <div class="input-group search-pill rounded-pill overflow-hidden shadow-sm border">
                            <span class="input-group-text bg-white border-0 pe-1 ps-3">
                                <i class="fas fa-search text-muted small"></i>
                            </span>
                            <input type="text" id="filtro-cliente" name="q"
                                class="form-control bg-white border-0 ps-2 py-2 shadow-none"
                                placeholder="Buscar pedido o cliente..." value="<?= htmlspecialchars($busqueda); ?>"
                                autocomplete="off">
                        </div>
                    </form>
                </div>

                <div class="col-12 col-md-7 col-lg-8">
                    <div class="contenedor-filtros-scroll d-flex justify-content-md-end gap-2 ps-1">
                        <?php
                        $filtros = [
                            'Todos' => 'Todos',
                            'Asignado' => 'Asignados',
                            'Recogido' => 'Recogidos',
                            'EnCamino' => 'En Camino',
                            'Entregado' => 'Entregados'
                        ];

                        // Obtenemos la búsqueda actual del GET para persistirla si fuera necesario
                        $q_actual = $_GET['q'] ?? '';

                        foreach ($filtros as $valor => $label):
                            $es_activo = ($estado_filtro === $valor);
                            $clase_btn = $es_activo ? 'btn-primary shadow active-filter' : 'btn-light border text-muted';
                            ?>
                            <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium <?= $clase_btn ?>"
                                onclick="filtrarPorEstado('<?= $valor ?>')">
                                <?= $label ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <pre style="display:none">
    <?php
    echo "Contenido de pedidos_en_proceso: ";
    print_r($pedidos_en_proceso[0] ?? 'ESTÁ VACÍO');
    ?>
</pre>
                <table class="table align-middle mb-0 table-minimalist">
                    <thead>
                        <tr class="text-muted">
                            <th class="ps-4 small text-uppercase fw-semibold" style="letter-spacing: 0.05em;">Código
                            </th>
                            <th class="small text-uppercase fw-semibold" style="letter-spacing: 0.05em;">Datos</th>
                            <th class="text-center small text-uppercase fw-semibold" style="letter-spacing: 0.05em;">
                                Estado</th>
                            <th class="text-center pe-4 small text-uppercase fw-semibold"
                                style="letter-spacing: 0.05em;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-pedidos-body">
                        <?php foreach ($pedidos_en_proceso as $pedido): ?>
                            <tr data-estado="<?= htmlspecialchars($pedido['estado']); ?>"
                                data-cliente="<?= htmlspecialchars($pedido['nombre_cliente']); ?>"
                                data-codigo="<?= htmlspecialchars($pedido['codigo_seguimiento']); ?>">

                                <td class="ps-4">
                                    <span class="code-badge">#<?= $pedido['codigo_seguimiento']; ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="text-dark fw-bold mb-0" style="font-size: 0.95rem;">
                                                <?= htmlspecialchars($pedido['nombre_cliente']); ?>
                                            </div>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="d-flex align-items-start text-muted"
                                                    style="font-size: 0.85rem;">
                                                    <i class="fas fa-map-pin text-danger mt-1 me-2"
                                                        style="font-size: 0.9rem;"></i>
                                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($pedido['direccion_entrega'] . ', Chacabuco, Buenos Aires'); ?>"
                                                        target="_blank"
                                                        class="text-muted text-decoration-none d-inline-block text-truncate"
                                                        style="max-width: 250px; border-bottom: 1px dashed #ced4da; transition: all 0.2s;"
                                                        onmouseover="this.style.color='#0d6efd'; this.style.borderBottomColor='#0d6efd'"
                                                        onmouseout="this.style.color='#6c757d'; this.style.borderBottomColor='#ced4da'">

                                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                        <?= htmlspecialchars($pedido['direccion_entrega'] ?? 'Sin dirección registrada'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <select
                                        class="form-select form-select-sm rounded-pill shadow-none estado-select mx-auto w-auto"
                                        onchange="confirmarCambioDinamico(this, <?= $pedido['id']; ?>, '<?= $pedido['estado']; ?>', this.closest('tr'))">
                                        <?php foreach (['Asignado', 'Recogido', 'EnCamino', 'Entregado'] as $est): ?>
                                            <option value="<?= $est; ?>" <?= ($pedido['estado'] == $est) ? 'selected' : ''; ?>>
                                                <?= $est; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <td class="pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn-circle btn-detalle btn-ver-detalle"
                                            onclick="verDetallePedido(<?= $pedido['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button type="button" class="btn-circle btn-editar"
                                            onclick="editarPedido(<?= $pedido['id']; ?>)">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <button type="button" class="btn-circle btn-eliminar"
                                            onclick="eliminarPedidoLogico(<?= $pedido['id']; ?>)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="py-3 px-4 bg-white border-top">
                <?php include __DIR__ . "/paginador_vista.php"; ?>
            </div>
        </div>
    </div>
</div>
</div>



<div class="modal fade" id="modalPedidos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div id="modal-body-content">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div id="contenidoEditar"></div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const notify = document.getElementById('mini-notificacion');
        if (notify) {
            setTimeout(() => {
                notify.style.animation = "fadeOut 0.5s forwards";
                setTimeout(() => notify.remove(), 500);
            }, 3000);
        }
    });
</script>