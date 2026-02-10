<?php
$id = $_GET['id'] ?? null;
if (!$id) exit("ID no válido");
$pedido = obtener_pedido_completo_por_id($id); 
if (!$pedido) exit("Pedido no encontrado");
?>

<form id="formActualizarPedido">
    <div class="modal-header border-0 pb-0">
        <h5 class="fw-bold">Editar Pedido #<?= $pedido['codigo_seguimiento'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <input type="hidden" name="id_pedido" value="<?= $pedido['id'] ?>">

        <div class="row g-2">
            <div class="col-12">
                <label class="form-label small fw-bold">Descripción General</label>
                <textarea name="descripcion_pedido" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($pedido['descripcion_pedido']) ?></textarea>
            </div>

            <h6 class="mt-4 text-primary small fw-bold text-uppercase">Dirección de Entrega</h6>
            
            <div class="col-md-8">
                <label class="form-label small text-muted">Calle</label>
                <input type="text" name="calle" class="form-control form-control-sm" value="<?= htmlspecialchars($pedido['calle']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted">Número</label>
                <input type="text" name="numero" class="form-control form-control-sm" value="<?= htmlspecialchars($pedido['numero']) ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label small text-muted">Ciudad</label>
                <input type="text" name="ciudad" class="form-control form-control-sm" value="<?= htmlspecialchars($pedido['ciudad']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label small text-muted">Código Postal</label>
                <input type="text" name="codigo_postal" class="form-control form-control-sm" value="<?= htmlspecialchars($pedido['codigo_postal']) ?>">
            </div>

            <div class="col-12">
                <label class="form-label small text-muted">Referencias / Indicaciones</label>
                <textarea name="referencias" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($pedido['referencias']) ?></textarea>
            </div>
        </div>
    </div>
    
    <div class="modal-footer border-0">
        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Guardar Cambios</button>
    </div>
</form>