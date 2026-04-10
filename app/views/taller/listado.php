<!DOCTYPE html>
<html>

<head>

    <title>Listado Talleres</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/taller.js"></script>
</head>

<body class="container mt-5">

    <nav class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="index.php?page=talleres" class="btn btn-outline-primary">Talleres</a>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="index.php?page=admin" class="btn btn-outline-secondary">Gestionar Solicitudes</a>
            <?php endif; ?>
        </div>
        <div>
            <span class="me-3"><?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user'] ?? 'Usuario') ?></span>
            <button id="btnLogout" class="btn btn-danger">Cerrar sesión</button>
        </div>
    </nav>

    <main>
        <h3>Talleres Disponibles</h3>


        <div id="mensaje" class="mb-3"></div>


        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cupo Máximo</th>
                    <th>Cupo Disponible</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="listaTalleres"></tbody>
        </table>
    </main>

</body>

</html>