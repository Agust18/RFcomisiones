<?php
$dimensiones = obtenerRangosDimensiones($conexion);
?>

<form action="<?php echo BASE_URL; ?>crear_pedido_action" method="POST" class="needs-validation" novalidate>
    <div class="row g-4">

        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                            <i class="fas fa-map-marker-alt fa-lg"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-0">Punto de Entrega</h5>
                    </div>

                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label small fw-bold text-muted">Calle</label>
                            <input type="text" name="calle" class="form-control border-0 bg-light" required
                                placeholder="Av. Central">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold text-muted">Número</label>
                            <input type="text" name="numero" class="form-control border-0 bg-light" required
                                placeholder="Altura de dirección">
                        </div>
                        <div class="col-7">
                            <label class="form-label small fw-bold text-muted">Ciudad</label>
                            <input type="text" name="ciudad" class="form-control border-0 bg-light" required
                                placeholder="Buenos Aires">
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-bold text-muted">Código Postal</label>
                            <input type="text" name="codigo_postal" class="form-control border-0 bg-light" required
                                placeholder="B6000">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Etiqueta de dirección</label>
                            <input type="text" name="etiqueta" class="form-control border-0 bg-light" required
                                placeholder="Casa, Oficina,Nombre de local ,Zona de entrega, etc.">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Referencias adicionales</label>
                            <textarea name="referencias" rows="2" class="form-control border-0 bg-light"
                                placeholder="Portón azul, timbre arriba, etc."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2 mt-2"> <?php if (!empty($dimensiones)): ?>
            <?php foreach ($dimensiones as $index => $dim): ?>
                <div class="col-12">
                    <input type="radio" class="btn-check" name="dimension_elegida" id="dim_<?= $dim['dimension_codigo'] ?>"
                        value="<?= $dim['dimension_codigo'] ?>" data-min="<?= $dim['precio_min'] ?>"
                        data-max="<?= $dim['precio_max'] ?>" <?= ($index === 0) ? 'checked' : '' ?>>

                    <label class="btn btn-light w-100 p-3 rounded-4 text-start d-flex align-items-center border-0 option-card"
                        for="dim_<?= $dim['dimension_codigo'] ?>">

                        <div class="selection-dot me-3"></div>

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-medium text-dark d-block mb-0"><?= $dim['nombre_visible'] ?></span>
                                    <span class="text-muted small" style="font-size: 0.8rem;">
                                        <?= $dim['medidas_texto'] ?>
                                    </span>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-dark d-block">
                                        $<?= number_format($dim['precio_min'], 0, ',', '.') ?>
                                    </span>
                                    <span class="text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                        RANGO MÁX. $<?= number_format($dim['precio_max'], 0, ',', '.') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 me-3">
                    <i class="fas fa-box fa-lg"></i>
                </div>
                <h5 class="card-title fw-bold mb-0">Detalles del Envío</h5>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold text-muted">¿Qué enviamos?</label>
                <textarea name="descripcion_general" rows="4" class="form-control border-0 bg-light" required
                    placeholder="Descripcion del producto,Cantidad y/o detalles a conocer"></textarea>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-white-50 small mb-0">Costo Estimado</p>
                    <h3 class="fw-bold mb-0" id="display_precio">$500.00</h3>
                    <input type="hidden" name="precio_envio" id="input_precio" value="500">
                </div>
                <button type="submit" class="btn btn-primary btn-md rounded-pill px-4">
                    Confirmar <i class="fas fa-chevron-right ms-2 small"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="<?php echo BASE_URL; ?>cliente"
            class="text-muted small btn btn-outline-danger mb-1 text-decoration-none">
            <i class="fas fa-times me-1"></i> Cancelar y volver
        </a>
    </div>
    </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const displayPrecio = document.getElementById('display_precio');
        const inputOculto = document.getElementById('input_precio');
        const radios = document.querySelectorAll('input[name="dimension_elegida"]');

        function actualizarInterfaz() {
            // Buscamos cuál está seleccionado
            const seleccionado = document.querySelector('input[name="dimension_elegida"]:checked');
            if (seleccionado) {
                let pMin = seleccionado.getAttribute('data-min');
                let pMax = seleccionado.getAttribute('data-max');

                // Actualiza el texto visual con el rango
                displayPrecio.innerText = '$' + pMin + ' - $' + pMax;

                // Guardamos el mínimo como base inicial en el input oculto
                inputOculto.value = pMin;
            }
        }

        // Escuchar cambios
        radios.forEach((elem) => {
            elem.addEventListener("change", actualizarInterfaz);
        });

        // Ejecutar al cargar la página para que no aparezca el $500 fijo del HTML
        actualizarInterfaz();
    });
</script>