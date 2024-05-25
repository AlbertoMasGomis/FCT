<?php
include("header.php");

if ($_SESSION['rol'] != "admin") {
    header("Location: index.php");
    exit();
}

// Definir la cantidad de resultados por página
$resultados_por_pagina = 2;

// Obtener el número total de usuarios
$total_usuarios = obtenerTotalUsuarios();

// Calcular el número total de páginas
$total_paginas_usuarios = ceil($total_usuarios / $resultados_por_pagina);

// Obtener el número de página actual de usuarios
$pagina_usuario = isset($_GET['pagina_usuario']) ? $_GET['pagina_usuario'] : 1;
$inicio_usuario = ($pagina_usuario - 1) * $resultados_por_pagina;

// Obtener los usuarios de la página actual
$usuarios = obtenerUsuariosPaginacion($inicio_usuario, $resultados_por_pagina);

// Obtener el número total de empresas
$total_empresas = obtenerTotalEmpresas();

// Calcular el número total de páginas
$total_paginas_empresas = ceil($total_empresas / $resultados_por_pagina);

// Obtener el número de página actual de empresas
$pagina_empresa = isset($_GET['pagina_empresa']) ? $_GET['pagina_empresa'] : 1;
$inicio_empresa = ($pagina_empresa - 1) * $resultados_por_pagina;

// Obtener las empresas de la página actual
$empresas = obtenerEmpresasPaginacion($inicio_empresa, $resultados_por_pagina);

// Función para obtener el número total de usuarios
function obtenerTotalUsuarios()
{
    // Realizar la consulta SQL para obtener el número total de usuarios
    $conexion = conectarBD();
    $sql = "SELECT COUNT(*) AS total FROM usuarios";
    $resultado = $conexion->query($sql);
    $row = $resultado->fetch_assoc();
    return $row['total'];
}

// Función para obtener el número total de empresas
function obtenerTotalEmpresas()
{
    // Realizar la consulta SQL para obtener el número total de empresas
    $conexion = conectarBD();
    $sql = "SELECT COUNT(*) AS total FROM empresa";
    $resultado = $conexion->query($sql);
    $row = $resultado->fetch_assoc();
    return $row['total'];
}

// Función para obtener usuarios con paginación
function obtenerUsuariosPaginacion($inicio, $limit)
{
    // Realizar la consulta SQL para obtener usuarios con paginación
    $conexion = conectarBD();
    $sql = "SELECT * FROM usuarios LIMIT ?, ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $inicio, $limit);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $usuarios = array();
    while ($row = $resultado->fetch_assoc()) {
        $usuarios[] = $row;
    }
    return $usuarios;
}

// Función para obtener empresas con paginación
function obtenerEmpresasPaginacion($inicio, $limit)
{
    // Realizar la consulta SQL para obtener empresas con paginación
    $conexion = conectarBD();
    $sql = "SELECT * FROM empresa LIMIT ?, ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $inicio, $limit);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $empresas = array();
    while ($row = $resultado->fetch_assoc()) {
        $empresas[] = $row;
    }
    return $empresas;
}

?>

<div class="containerAll">
<div class="container mt-5">
    <h2>Usuarios</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario) : ?>
                    <tr>
                        <td><?php echo $usuario['ID']; ?></td>
                        <td><?php echo $usuario['Nombre']; ?></td>
                        <td>
                            <a href="eliminarUsuario.php?id=<?php echo $usuario['ID']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Paginación -->
    <nav aria-label="Page navigation example" class="pageNavigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_paginas_usuarios; $i++) : ?>
                <li class="page-item <?php if ($i == $pagina_usuario) echo 'active'; ?>"><a class="page-link" href="?pagina_usuario=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<div class="container mt-5">
    <h2>Empresas</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empresas as $empresa) : ?>
                    <tr>
                        <td><?php echo $empresa['ID']; ?></td>
                        <td><?php echo $empresa['Nombre']; ?></td>
                        <td>
                            <a href="eliminarEmpresa.php?id=<?php echo $empresa['ID']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Paginación -->
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_paginas_empresas; $i++) : ?>
                <li class="page-item <?php if ($i == $pagina_empresa) echo 'active'; ?>"><a class="page-link" href="?pagina_empresa=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</div>

<?php
include("footer.php");
?>
