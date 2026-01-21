<?php
$id = $_GET['id'] ?? null;

if (!$id) {
    die("<div class='p-4 text-center text-danger'>Error: ID de pedido no proporcionado.</div>");
}

// 1. Buscamos los datos del pedido
$pedido = obtener_pedido_completo_por_id($id);

if (!$pedido) {
    die("<div class='p-4 text-center text-warning'>No se encontró el pedido #$id en el sistema.</div>");
}

$clase_estado = function_exists('get_badge_clase') ? get_badge_clase($pedido['estado']) : 'bg-secondary';
?>

<div class="modal-header border-0 bg-light">
    <div class="d-flex align-items-center">
        <div class="bg-primary text-white rounded-3 p-2 me-3">
            <i class="fas fa-box-open fa-lg"></i>
        </div>
        <div>
            <h5 class="modal-title fw-bold text-dark">Pedido
                #<?= htmlspecialchars($pedido['codigo_seguimiento'] ?? $pedido['id']) ?></h5>
            <small class="text-muted">
                <i class="far fa-calendar-alt me-1"></i>
                <?= date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])) ?>
            </small>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-4">
    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <div class="card border-0 bg-light h-100">
                <div class="card-body p-3">
                    <span class="text-muted small d-block mb-1">Estado del envío</span>
                    <span class="badge rounded-pill <?= $clase_estado ?> px-3 py-2">
                        <?= htmlspecialchars($pedido['estado']) ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card border-0 bg-light h-100">
                <div class="card-body p-3">
                    <span class="text-muted small d-block mb-1">Tamaño del bulto</span>
                    <span class="fw-bold text-dark">
                        <i class="fas fa-boxes me-1 text-primary"></i>
                        Tamaño <?= htmlspecialchars($pedido['dimension_codigo'] ?? 'S') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h6 class="fw-bold text-dark mb-3">
            <i class="fas fa-map-marker-alt me-2 text-danger"></i>Destino de Entrega
        </h6>
        <div class="p-3 border rounded-3 bg-white shadow-sm">
            <p class="mb-1 fw-bold text-uppercase">Calle y numero :
                <?= htmlspecialchars(($pedido['calle'] ?? '') . " " . ($pedido['numero'] ?? '')) ?>
            </p>
            <p class="text-muted small mb-1">Ciudad : 
                <?= htmlspecialchars($pedido['ciudad'] ?? 'No especificada') ?>
            </p>
            <p class="text-muted small mb-1">Etiqueta  <?= htmlspecialchars($pedido['etiqueta'] ?? 'No especificada') ?><br> Referencias:
               
                   <?= htmlspecialchars($pedido['referencias'] ?? 'No especificada') ?>
            </p>
        </div>
    </div>
    <div class="mb-0">
    <h6 class="fw-bold text-dark mb-3">
        <i class="fas fa-list-ul me-2 text-primary"></i>Detalle del Contenido
    </h6>
    <div class="table-responsive">
        <table class="table table-hover border align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="small fw-bold px-3 text-center" width="80">CANT.</th>
                    <th class="small fw-bold">DESCRIPCIÓN</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // DEBUG: Si quieres ver qué trae la variable, quita las barras de la línea de abajo:
                // echo "";

                $items_raw = $pedido['descripcion_pedido'] ?? ''; 
                
                if (!empty(trim($items_raw))):
                    // Separamos por el doble pipe
                    $items = explode('||', $items_raw);
                    
                    foreach ($items as $item):
                        $item = trim($item);
                        if (empty($item)) continue;

                        // Separamos por el guion (limpiamos espacios extras)
                        $detalles = explode('-', $item, 2);
                        
                        // Si hay guion lo separamos, si no, ponemos 1 como cantidad por defecto
                        $cant = (count($detalles) >= 2) ? trim($detalles[0]) : 'no hay cantidad';
                        $desc = (count($detalles) >= 2) ? trim($detalles[1]) : trim($detalles[0]);
                        ?>
                        <tr>
                            <td class="text-center fw-bold px-3"><?= htmlspecialchars($cant) ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($desc) ?></td>
                        </tr>
                    <?php endforeach; 
                else: ?>
                    <tr>
                        <td colspan="2" class="text-center text-muted py-3 small">
                            No hay productos registrados en este pedido.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<div class="modal-footer border-0 bg-light d-flex justify-content-between p-3">
    <div class="ps-2">
        <?php
        // Usamos los nombres reales de tus columnas
        $precio = $pedido['precio_final'] ?? 0;
        $estado_p = $pedido['estado_precio'] ?? 'Estimado';

        if ($precio > 0): ?>
            <span class="text-muted small d-block">
                <?= ($estado_p == 'Estimado') ? 'Costo estimado:' : 'Costo final de envío:' ?>
            </span>
            <div class="d-flex align-items-center">
                <span class="fw-bold <?= ($estado_p == 'Estimado') ? 'text-dark' : 'text-success' ?> fs-4">
                    $<?= number_format($precio, 0, ',', '.') ?>
                </span>
                <?php if ($estado_p == 'Estimado'): ?>
                    <span class="ms-2 badge bg-warning text-dark" style="font-size: 0.65rem; padding: 4px 8px;">POR
                        CONFIRMAR</span>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <span class="text-muted small italic">Costo pendiente de asignación</span>
        <?php endif; ?>
    </div>
    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
</div>


