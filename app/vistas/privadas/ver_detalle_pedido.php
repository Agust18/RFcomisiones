<?php
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<div class='p-3 text-danger'>ID de pedido no proporcionado.</div>";
    exit;
}
$pedido = obtener_pedido_completo_por_id($id);
if (!$pedido) {
    echo "<div class='alert alert-warning m-3'>El pedido #$id no existe o no tiene datos asociados.</div>";
    exit;
}
$cliente = obtener_usuario_por_id($pedido['id_usuario'] ?? 0);
$mensaje_ws = "Hola, soy el comisionista de tu pedido #" . ($pedido['codigo_seguimiento'] ?? $id) . ". Estoy en camino.";
$url_whatsapp = "https://wa.me/" . preg_replace('/[^0-9]/', '', $cliente['telefono']) . "?text=" . urlencode($mensaje_ws);
?>
<div class="modal-header border-0 bg-light">
    <h5 class="fw-bold mb-0">
        <i class="fas fa-receipt me-2 text-primary"></i>Detalle Pedido
        #<?= htmlspecialchars($pedido['codigo_seguimiento'] ?? $id) ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-4">
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="bg-primary bg-opacity-10 p-3 rounded-4 border-start border-primary border-4">
                <label class="text-muted small d-block mb-1 text-uppercase fw-bold">Cliente</label>
                <p class="fw-bold text-dark mb-0 fs-5">
                    <i class="fas fa-user-circle me-2 text-primary"></i>
                    <?= htmlspecialchars($pedido['nombre_cliente'] ?? 'Cliente no identificado') ?>
                </p>
                <?php if (!empty($cliente['telefono'])): ?>
                    <a href="<?= $url_whatsapp ?>" target="_blank"
                        class="btn btn-success bg-opacity-25 text-white border-0 btn-sm rounded-pill px-3 fw-bold"
                        style="font-size: 0.75rem;">
                        <i class="fab fa-whatsapp me-1"></i> WhatsApp

                    </a>
                <?php endif; ?>

            </div>
        </div>

        <div class="col-md-6">
            <label class="text-muted small d-block">Fecha de Creación</label>
            <p class="fw-bold mb-0">
                <i class="far fa-calendar-alt me-1 text-muted"></i>
                <?= !empty($pedido['fecha_creacion']) ? date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])) : 'Fecha no disponible' ?>
            </p>
        </div>

        <div class="col-md-6 text-md-end">
            <label class="text-muted small d-block text-md-end">Estado Actual</label>
            <span class="badge rounded-pill bg-primary px-3 py-2">
                <?= htmlspecialchars($pedido['estado'] ?? 'Pendiente') ?>
            </span>
        </div>

        <div class="col-12">
            <label class="text-muted small d-block">Descripción General</label>
            <p class="text-dark bg-light p-2 rounded small">
                <?= htmlspecialchars($pedido['descripcion_pedido'] ?? 'Sin descripción adicional') ?>
            </p>
        </div>
    </div>



    <hr class="opacity-10">

    <div class="mb-4">
        <h6 class="fw-bold text-primary small text-uppercase mb-3">
            <i class="fas fa-map-marked-alt me-2"></i>Dirección de Entrega
            (<?= htmlspecialchars($pedido['etiqueta'] ?? 'Principal') ?>)
        </h6>
        <div class="ps-3 border-start border-2 border-light">
            <p class="mb-1 fw-bold text-dark">
                <?= htmlspecialchars(($pedido['calle'] ?? 'S/N') . " " . ($pedido['numero'] ?? '') . ", " . ($pedido['ciudad'] ?? '')) ?>
            </p>
            <p class="small text-muted mb-2">Código Postal: <?= htmlspecialchars($pedido['codigo_postal'] ?? 'N/A') ?>
            </p>

            <?php if (!empty($pedido['referencias'])): ?>
                <div class="bg-light p-2 rounded small mt-2">
                    <strong class="text-primary"><i class="fas fa-info-circle me-1"></i>Referencias:</strong>
                    <?= htmlspecialchars($pedido['referencias']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <hr class="opacity-10">

    <div class="mb-3">
        <h6 class="fw-bold text-primary small text-uppercase mb-3">
            <i class="fas fa-box me-2"></i>Contenido del Paquete
        </h6>
        <div class="table-responsive rounded-3 border">
            <table class="table table-sm table-hover mb-0">
                <thead class="bg-light small">
                    <tr>
                        <th class="px-3 py-2" width="80">Cant.</th>
                        <th class="py-2">Descripción Item</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($pedido['items'])):
                        $lista_items = explode('||', $pedido['items']);
                        foreach ($lista_items as $item_crudo):
                            $partes = explode(' - ', $item_crudo, 2);
                            if (count($partes) === 2): ?>
                                <tr>
                                    <td class="px-3 fw-bold text-primary"><?= htmlspecialchars($partes[0]) ?></td>
                                    <td><?= htmlspecialchars($partes[1]) ?></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="px-3 small text-muted fst-italic"><?= htmlspecialchars($item_crudo) ?>
                                    </td>
                                </tr>
                            <?php endif;
                        endforeach;
                    else: ?>
                        <tr>
                            <td colspan="2" class="text-center py-4 text-muted">
                                <i class="fas fa-box-open d-block mb-2 fa-2x opacity-25"></i>
                                No hay productos registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<div class="mt-4 p-3 rounded-4 bg-white border shadow-sm">
    <div class="row align-items-center">
        <div class="col-md-6">
            <label class="text-muted small d-block mb-1 text-uppercase fw-bold">Costo de Envío</label>
            <div class="d-flex align-items-center">
                <span
                    class="fs-3 fw-bold text-dark me-2">$<?= number_format($pedido['precio_final'], 0, ',', '.') ?></span>
                <span
                    class="badge <?= ($pedido['estado_precio'] == 'Estimado') ? 'bg-warning text-dark' : 'bg-success' ?> rounded-pill">
                    <i
                        class="fas <?= ($pedido['estado_precio'] == 'Estimado') ? 'fa-clock' : 'fa-check-circle' ?> me-1"></i>
                    <?= htmlspecialchars($pedido['estado_precio'] ?? 'Estimado') ?>
                </span>
            </div>
            <small class="text-muted">Tamaño elegido:
                <strong><?= htmlspecialchars($pedido['dimension_elegida'] ?? 'S') ?></strong></small>
        </div>

        <div class="col-md-6 border-start ps-4">
            <form action="app/acciones/confirmar_precio_handler.php" method="POST">
                <input type="hidden" name="id_pedido" value="<?= $id ?>">
                <label class="form-label small fw-bold text-primary">Ajustar / Confirmar Precio:</label>
                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text">$</span>
                    <input type="number" name="nuevo_precio" class="form-control fw-bold"
                        value="<?= $pedido['precio_final'] ?>" step="0.01" required>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-save me-1"></i> Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal-header border-0 bg-light d-flex justify-content-between align-items-center">
    <h5 class="fw-bold mb-0">
        <i class="fas fa-receipt me-2 text-primary"></i>Detalle Pedido
        <span class="text-primary">#<?= htmlspecialchars($pedido['codigo_seguimiento'] ?? $id) ?></span>
    </h5>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle" onclick="window.print();"
            title="Imprimir Comprobante">
            <i class="fas fa-print"></i>
        </button>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
</div>