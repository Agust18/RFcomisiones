<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "app/includes/head.php";
?>
<body>
    <!-- NAV SUPERIOR -->
    <nav class="navbar navbar-dark bg-dark px-3 shadow">
        <button class="btn btn-outline-light d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
            <i class="bi bi-list fs-3"></i>
        </button>
        <span class="navbar-brand fw-semibold">Panel de Administración</span>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <!-- ===== SIDEBAR DESKTOP ===== -->
            <aside class="col-lg-3 col-xl-2 sidebar d-none d-lg-block">
                <h4 class="text-white text-center border-bottom">ADMIN</h4>

                <ul class="nav flex-column px-2 mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-people me-2"></i>Usuarios</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-box-seam me-2"></i>Productos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-journal-text me-2"></i>Órdenes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-gear me-2"></i>Configuración</a>
                    </li>
                </ul>
            </aside>

            <!-- ===== SIDEBAR MÓVIL (OFFCANVAS) ===== -->
            <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarOffcanvas">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title fw-bold">ADMIN</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>

                <div class="offcanvas-body">

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="#"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="#"><i class="bi bi-people me-2"></i>Usuarios</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="#"><i class="bi bi-box-seam me-2"></i>Productos</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="#"><i class="bi bi-journal-text me-2"></i>Órdenes</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="#"><i class="bi bi-gear me-2"></i>Configuración</a>
                        </li>
                    </ul>

                </div>
            </div>

            <main class="col-lg-9 col-xl-10 p-4">
                <h1 class="fw-bold mb-3">Dashboard</h1>

                <div class="card shadow-sm p-4">
                    <h5 class="fw-semibold">Bienvenido al panel</h5>
                    <p class="text-secondary">Aquí iría tu contenido principal, métricas, gráficas o lo que quieras mostrar.</p>
                </div>
            </main>

        </div>
    </div>
</body>
</html>
