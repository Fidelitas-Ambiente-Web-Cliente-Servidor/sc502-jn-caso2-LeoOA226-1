<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Solicitudes pendientes</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/solicitud.js"></script>
</head>
<body class="container mt-5">
    <nav class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="index.php?page=talleres" class="btn btn-outline-primary">Talleres</a>
            <a href="index.php?page=admin" class="btn btn-outline-secondary">Gestionar Solicitudes</a>
        </div>
        <div>
            <span class="me-3">Admin: <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user'] ?? 'Administrador') ?></span>
            <button id="btnLogout" class="btn btn-danger">Cerrar sesión</button>
        </div>
    </nav>
    
    <main>
        <h2>Solicitudes pendientes de aprobación</h2>

        <div id="mensaje" class="mb-3"></div>
        
        <div class="table-container">
            <table id="tabla-solicitudes" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Taller</th>
                        <th>Solicitante</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="solicitudes-body">
                    <tr>
                        <td colspan="5" class="loader">Cargando solicitudes...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <div id="mensaje"></div>

    
</body>
</html>