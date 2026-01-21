<?php
if (isset($_SESSION['notificacion'])): 
    $n = $_SESSION['notificacion'];
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Usamos json_encode para que la URL 'http://...' no rompa el script
        const icon = <?= json_encode($n['icon']) ?>;
        const title = <?= json_encode($n['title']) ?>;
        const textoHtml = <?= json_encode($n['text']) ?>;

        Swal.fire({
            icon: icon,
            title: title,
            html: textoHtml, // Aquí se renderizará tu link correctamente
            showConfirmButton: true,
            confirmButtonText: 'Entendido'
        });
    });
</script>
<?php 
    unset($_SESSION['notificacion']); 
endif; 
?>