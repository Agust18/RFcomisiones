<nav aria-label="Navegación de pedidos" class="mt-4 mb-2">
    <ul class="pagination justify-content-center align-items-center gap-4">

        <?php
        // 1. Preparamos los parámetros actuales para no perder nada
        $params_prev = $_GET;
        $params_prev['p'] = $pagina_actual - 1; // Bajamos una página
        
        $params_next = $_GET;
        $params_next['p'] = $pagina_actual + 1; // Subimos una página
        ?>

        <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?<?php echo http_build_query($params_prev); ?>">
                <i class="bi bi-chevron-left fs-5"></i>
            </a>
        </li>

        <li class="page-item mx-2">
            <span class="text-dark fw-semibold" style="font-size: 0.85rem;">
                Página <span class="text-primary"><?php echo $pagina_actual; ?></span>
                <span class="text-muted fw-light mx-1">de</span> <?php echo $total_paginas; ?>
            </span>
        </li>

        <li class="page-item <?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
            <a class="page-link ..." href="index.php?<?php echo http_build_query($params_next); ?>"> <i
                    class="bi bi-chevron-right fs-5"></i>
            </a>
        </li>

    </ul>
</nav>