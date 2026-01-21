<nav aria-label="Navegación de pedidos">
    <ul class="pagination pagination-sm justify-content-center mb-0">

        <?php
        // Esta es la clave: construimos la base de la URL con los filtros actuales
        // $url_base = "?estado=" . urlencode($estado_filtro) . "&q=" . urlencode($busqueda); 
        // $url_base = BASE_URL . $pagina . "?estado=" . urlencode($estado_filtro) . "&q=" . urlencode($busqueda); 
        $slug_actual = $_GET['pagina'] ?? 'inicio';

        // Construimos la URL con la base, el nombre de la página y los filtros
        $url_base = BASE_URL . $slug_actual . "?estado=" . urlencode($estado_filtro) . "&q=" . urlencode($busqueda);
        ?>

        <li class="page-item <?= ($pagina_actual <= 1) ? 'disabled' : '' ?>">
            <a class="page-link border-0 rounded-pill me-2 shadow-sm"
                href="<?= $url_base ?>&p=<?= $pagina_actual - 1 ?>">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>

        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?= ($pagina_actual == $i) ? 'active' : '' ?>">
                <a class="page-link border-0 rounded-pill mx-1 shadow-sm <?= ($pagina_actual == $i) ? 'bg-primary text-white' : 'text-muted' ?>"
                    href="<?= $url_base ?>&p=<?= $i ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?= ($pagina_actual >= $total_paginas) ? 'disabled' : '' ?>">
            <a class="page-link border-0 rounded-pill ms-2 shadow-sm"
                href="<?= $url_base ?>&p=<?= $pagina_actual + 1 ?>">
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>

    </ul>
</nav>