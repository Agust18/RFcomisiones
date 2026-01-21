<?php
require_once __DIR__ . '/../../funciones/funciones.php';
// El ID del cliente lo obtenemos de la sesión que se creó al loguearse
$id_cliente = $_SESSION['id_usuario'] ?? 0;
require_once 'app/acciones/paginador_cliente.php';
// Obtenemos los pedidos usando la nueva función
// $pedidos = obtener_pedidos_cliente($id_cliente);
// Obtenemos mensajes de éxito o error de la sesión


//Logica de conteo de pedidos para el dashboard
//que hace? Cuenta los pedidos en memoria para evitar múltiples consultas a la base de datos
$total_activos = contar_pedidos_activos_memoria($pedidos);
$pendientes_asignar = contar_pedidos_por_estado_memoria($pedidos, 'Pendiente');
$entregados_completados = contar_pedidos_por_estado_memoria($pedidos, 'Completado');

?>
<div class="container py-4">
    <nav class="navbar navbar-expand-lg bg-white border-bottom py-2 shadow-sm">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand fs-6" href="#">
                <span class="text-muted fw-light">Hola,</span>
                <span
                    class="fw-bold text-dark"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Invitado'); ?></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCliente">
                <span class="navbar-toggler-icon" style="width: 1.2em;"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCliente">
                <div class="d-grid d-lg-flex justify-content-lg-end align-items-center gap-2 ms-auto mt-3 mt-lg-0">

                    <a href="<?php echo BASE_URL; ?>nuevo_pedido"
                        class="btn btn-primary btn-sm rounded-pill px-3 py-2 fw-medium shadow-sm">
                        <i class="fas fa-plus me-1"></i> Nuevo Pedido
                    </a>

                    <a href="#" onclick="confirmarCierreSesion(event)"
                        class="btn btn-link text-danger text-decoration-none btn-sm fw-medium mt-2 mt-lg-0">
                        <i class="fas fa-sign-out-alt me-1"></i> Salir
                    </a>

                </div>
            </div>
        </div>
    </nav>

    <div class="row g-3 mb-4 mt-1 flex-nowrap overflow-auto pb-2 scroll-movil"
        style="scrollbar-width: none; -ms-overflow-style: none;">

        <div class="col-8 col-md-4 flex-shrink-0">
            <div class="card border-0 shadow-sm h-100" style="background: #eef9ff; border-radius: 16px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px;">
                            <i class="fas fa-list-alt fa-xs"></i>
                        </div>
                        <span class="ms-2 text-dark fw-bold small">En Curso</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-0"><?= $total_activos; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-8 col-md-4 flex-shrink-0">
            <div class="card border-0 shadow-sm h-100" style="background: #fff5f5; border-radius: 16px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px;">
                            <i class="fas fa-hourglass-half fa-xs"></i>
                        </div>
                        <span class="ms-2 text-dark fw-bold small">Pendientes</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-0"><?= $pendientes_asignar; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-8 col-md-4 flex-shrink-0">
            <div class="card border-0 shadow-sm h-100" style="background: #f0fff4; border-radius: 16px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px;">
                            <i class="fas fa-check-circle fa-xs"></i>
                        </div>
                        <span class="ms-2 text-dark fw-bold small">Completados</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-0"><?= $entregados_completados; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-lg-4">
        <div class="row align-items-center g-3 mb-4 mt-4">
            <div class="col-12 col-md-6">
                <h5 class="fw-bold text-dark m-0 text-center text-md-start">Historial de Pedidos</h5>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-md-end">

                <form id="form-filtros" action="index.php" method="GET" class="w-100">
                    <input type="hidden" name="pagina" value="cliente">
                    <input type="hidden" name="estado" id="input-estado"
                        value="<?= htmlspecialchars($estado_filtro) ?>">
                    <input type="hidden" name="p" value="1">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border bg-white">
                        <span class="input-group-text bg-white border-0 pe-0">
                            <i class="fas fa-search text-muted small"></i>
                        </span>

                        <input type="text" id="filtro-para-clientes" name="q"
                            class="form-control bg-white border-0 ps-2 py-2 shadow-none" placeholder="Buscar..."
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" autocomplete="off">

                        <?php if (!empty($_GET['q']) || (isset($_GET['estado']) && $_GET['estado'] !== 'Todos')): ?>
                            <a href="index.php?pagina=cliente" class="btn bg-white border-0 text-muted px-3">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex overflow-auto gap-2 mb-4 pb-2"
            style="scrollbar-width: none; -ms-overflow-style: none; white-space: nowrap; -webkit-overflow-scrolling: touch;">
            <?php
            $estado_actual = $_GET['estado'] ?? 'Todos';
            $busqueda_actual = $_GET['q'] ?? ''; // Mantenemos lo que el usuario escribió
            
            $botones = [
                'Todos' => ['class' => 'bg-dark text-white', 'label' => 'Todos'],
                'Asignado' => ['class' => 'bg-info-subtle text-info border-0', 'label' => 'Asignados'],
                'Recogido' => ['class' => 'bg-primary-subtle text-primary border-0', 'label' => 'Recogidos'],
                'EnCamino' => ['class' => 'bg-warning-subtle text-warning border-0', 'label' => 'En Camino'],
                'Entregado' => ['class' => 'bg-success-subtle text-success border-0', 'label' => 'Entregados'],
            ];

            foreach ($botones as $valor => $info):
                $es_activo = ($estado_actual === $valor);
                // Construimos la URL aquí mismo para que no haya errores de JS
                $url_filtro = "index.php?pagina=cliente&estado=" . $valor . "&q=" . urlencode($busqueda_actual) . "&p=1";
                ?>
                <a href="<?= $url_filtro ?>"
                    class="btn btn-sm rounded-pill mt-1 px-3 <?= $info['class'] ?> btn-filtro-estado <?= $es_activo ? 'active-filter' : '' ?>"
                    style="text-decoration: none;">
                    <?= $info['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>




        <?php if (empty($pedidos)): ?>
            <div class="card border-0 shadow-sm text-center p-5 rounded-4">
                <div class="card-body">
                    <i class="fas fa-box-open fa-3x text-light mb-3"></i>
                    <p class="text-muted">Aún no tienes pedidos registrados.</p>
                    <a href="<?= BASE_URL; ?>nuevo_pedido" class="btn btn-primary btn-sm rounded-pill px-4">Nuevo pedido</a>
                </div>
            </div>
        <?php else: ?>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3 text-uppercase small fw-bold text-muted">Código</th>
                                <th class="border-0 py-3 text-uppercase small fw-bold text-muted">Estado</th>
                                <th class="border-0 py-3 text-uppercase small fw-bold text-muted d-none d-md-table-cell">
                                    Comisionista</th>
                                <th class="border-0 py-3 text-uppercase small fw-bold text-muted text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-pedidos-body" class="border-top-0">
                            <?php foreach ($pedidos as $pedido):
                                $estado = $pedido['estado'] ?? 'Pendiente';
                                $clase_estado = get_badge_clase($estado);
                                $codigo = $pedido['codigo_seguimiento'] ?? 'S/N';
                                ?>
                                <tr data-codigo="<?= $pedido['codigo_seguimiento'] ?>"
                                    data-comisionista="<?= $pedido['nombre_comisionista'] ?>">

                                    <td class="px-4 py-3">
                                        <span class="fw-bold text-primary d-block">#<?= htmlspecialchars($codigo); ?></span>
                                        <small
                                            class="text-muted small"><?= date('d/m/Y', strtotime($pedido['fecha_creacion'])); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill <?= $clase_estado; ?> px-2 py-1 fw-medium"
                                            style="font-size: 0.75rem;">
                                            <?= htmlspecialchars($estado); ?>
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span
                                            class="small text-dark"><?= htmlspecialchars($pedido['nombre_comisionista'] ?? 'Buscando...'); ?></span>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <?php if (!empty($pedido['telefono_comisionista'])): ?>
                                                <?php if (!empty($pedido['telefono_comisionista'])):
                                                    // 1. Limpiar el teléfono
                                                    $tel = preg_replace('/[^0-9]/', '', $pedido['telefono_comisionista']);

                                                    // 2. Preparar el mensaje (Personalizalo a tu gusto)
                                                    $nombre_cliente = $_SESSION['nombre'] ?? 'un cliente';
                                                    $nombre_comisionista = $pedido['nombre_comisionista'] ?? 'Comisionista';
                                                    $msj_whatsapp = "Hola " . $nombre_comisionista . ", soy " . $nombre_cliente . ", te consulto por mi pedido #" . $codigo;

                                                    // 3. Crear la URL final
                                                    $url_final = "https://wa.me/" . $tel . "?text=" . urlencode($msj_whatsapp);
                                                    ?>
                                                    <a href="<?= $url_final ?>" target="_blank"
                                                        class="btn p-0 d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                                        style="width: 32px; height: 32px; background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; flex-shrink: 0;"
                                                        title="Consultar por WhatsApp">
                                                        <i class="fab fa-whatsapp" style="font-size: 0.9rem;"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <button type="button"
                                                class="btn p-0 d-flex align-items-center justify-content-center rounded-circle shadow-sm btn-ver-detalle"
                                                data-id="<?= $pedido['id']; ?>"
                                                style="width: 32px; height: 32px; background-color: #f0f7ff; color: #0d6efd; border: 1px solid #d0e3ff; flex-shrink: 0; cursor: pointer; position: relative; z-index: 10;">
                                                <i class="fas fa-eye" style="font-size: 0.85rem;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="py-3 px-4 border-top">
                    <?php include __DIR__ . "/paginador_cliente_vista.php"; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>


</div>

<div class="modal fade" id="modalDetallePedido" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" id="contenidoModalDetalle">
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. BÚSQUEDA EN TIEMPO REAL (Visual)
        const inputBusqueda = document.getElementById('filtro-para-clientes');
        const tablaBody = document.getElementById('tabla-pedidos-body');

        if (inputBusqueda && tablaBody) {
            inputBusqueda.addEventListener('input', function () {
                const filtro = this.value.toLowerCase().trim();
                const filas = tablaBody.querySelectorAll('tr');
                filas.forEach(fila => {
                    const codigo = (fila.getAttribute('data-codigo') || "").toLowerCase();
                    const comisionista = (fila.getAttribute('data-comisionista') || "").toLowerCase();
                    fila.style.display = (codigo.includes(filtro) || comisionista.includes(filtro)) ? '' : 'none';
                });
            });
        }


    });

    // 3. FILTRO POR ESTADO (La función que te sacaba)
    window.filtrarPorEstado = function (valor) {
        // Obtenemos si hay algo escrito en la búsqueda para no perderlo
        const q = document.getElementById('filtro-para-clientes').value.trim();

        // CONSTRUCCIÓN MANUAL DE URL: Esto es infalible para los permisos
        const destino = `index.php?pagina=cliente&estado=${valor}&q=${encodeURIComponent(q)}&p=1`;

        console.log("Redirigiendo a:", destino); // Para que revises en consola si falla
        window.location.href = destino;
    }
</script>