# RFcomisiones
Sistema integral de gestión de pedidos y comisiones desarrollado en PHP y MySQL. Incluye arquitectura de rutas amigables, panel administrativo con filtros avanzados, sistema de paginación dinámica y recuperación de contraseñas mediante tokens de seguridad. Interfaz moderna y responsiva con Bootstrap 5 y SweetAlert2.

RFcomisiones - Sistema de Gestión de Pedidos y Ventas
Este es mi primer proyecto real, nacido de la necesidad de organizar y digitalizar el flujo de pedidos de una manera eficiente. Me enfoqué en construir una herramienta que sea fácil de usar pero que por dentro tenga una estructura sólida y profesional.

💡 El Objetivo
El proyecto centraliza la recepción de pedidos y permite llevar un control claro de los estados de venta. Mi meta principal fue aprender a conectar una base de datos de forma segura y crear una navegación fluida para el usuario.

🧠 Decisiones Técnicas y Aprendizajes
Para este desarrollo, no me conformé con lo básico y decidí implementar soluciones que se usan en entornos profesionales:

Arquitectura de Punto de Entrada Único: Implementé un index.php que centraliza todas las peticiones, permitiéndome gestionar la seguridad y las sesiones de forma global.

URLs Profesionales: Configuré rutas amigables mediante .htaccess. Esto hace que el sistema no solo se vea mejor (sin el .php al final), sino que sea más seguro.

Gestión de Estados: Programé la lógica para que los pedidos puedan seguir un flujo de trabajo, permitiendo al administrador tener una visión clara del negocio en tiempo real.

Comunicación y Feedback: Integré PHPMailer para el manejo de correos y SweetAlert2 para que las confirmaciones y errores no sean simples mensajes de texto, sino alertas interactivas.

Seguridad en el Flujo de Trabajo: Aprendí a manejar un entorno de Git profesional, separando las configuraciones sensibles de la base de datos del código público.

🛠️ Tecnologías que utilicé
PHP y MySQL: El motor de la aplicación y la gestión de datos.

JavaScript: Para mejorar la interactividad sin recargar la página constantemente.

Bootstrap 5: Para asegurar que el panel sea cómodo de usar desde cualquier dispositivo.

¿Cómo probarlo?
En la carpeta app/config/ dejé un archivo db.php.example con la estructura necesaria para conectar la base de datos. Solo hace falta renombrarlo a db.php y completar los datos locales.
