<?php
include("header.php");

if ($_SESSION['rol'] != "empresa") {
    header("Location: index.php");
    exit();
}



// Obtener el ID del postulante asociado al puesto de trabajo
function obtenerIdPostulante($id_empleo)
{
    // Realizar una consulta a la base de datos para obtener el ID del postulante
    $conexion = conectarBD();
    $sql = "SELECT IDUsuario FROM Postulaciones WHERE IDEmpleo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_empleo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró alguna postulación para este puesto de trabajo
    if ($resultado->num_rows > 0) {
        // Obtener el primer resultado (suponiendo que solo hay una postulación por puesto de trabajo)
        $fila = $resultado->fetch_assoc();
        // Devolver el ID del postulante
        return $fila['IDUsuario'];
    } else {
        // En caso de que no haya postulaciones para este puesto de trabajo, devolver un valor predeterminado
        return null;
    }
}

// Luego puedes usar esta función para obtener el ID del postulante en tu código


// Obtener el ID de la empresa de la sesión
$id_empresa = $_SESSION['empresa_id'];
$conexion = conectarBD();
// Consulta para obtener los puestos de trabajo de la empresa
$sql_puestos_trabajo = "SELECT * FROM Empleos WHERE IDEmpresa = $id_empresa";
$resultado_puestos_trabajo = $conexion->query($sql_puestos_trabajo);

// Consulta para obtener las postulaciones recibidas por cada puesto de trabajo
$sql_postulaciones = "SELECT e.ID, e.Titulo, COUNT(p.ID) AS TotalPostulaciones
                     FROM Empleos e
                     LEFT JOIN Postulaciones p ON e.ID = p.IDEmpleo
                     WHERE e.IDEmpresa = $id_empresa
                     GROUP BY e.ID, e.Titulo";
$resultado_postulaciones = $conexion->query($sql_postulaciones);

?>

<div class="container">
        <h3 class="texto-centrado mt-5 h1-responsive">Ver Puestos de Trabajo Publicados</h3>
    </div>

<div id="contenedor-form">

    <?php
    // Mostrar los puestos de trabajo
    if ($resultado_puestos_trabajo->num_rows > 0) {
        while ($row = $resultado_puestos_trabajo->fetch_assoc()) {
            echo "<div class='oferta-empleo'>";
            echo "<h3>{$row['Titulo']}</h3>";
            echo "<p>{$row['Descripcion']}</p>";
            echo "<p>{$row['Categoria']}</p>";

            // Obtener el ID del postulante asociado a este puesto de trabajo (supongamos que lo obtenemos de alguna manera)
            $id_postulante = obtenerIdPostulante($row['ID']); // Suponiendo que tienes una función para obtener el ID del postulante
    ?>

            <form action="verInfoPostulante.php" method="post">
                <input type="hidden" name="postulante_id" value="<?php echo $id_postulante; ?>">
                <input type="hidden" name="puesto_id" value="<?php echo $row['ID']; ?>">
                <button class="boton-m" type="submit" name="ver_postulaciones">Ver Postulaciones</button>
            </form>

    <?php
            echo "</div>";
        }
    } else {
        echo "<p>No se encontraron puestos de trabajo publicados por la empresa.</p>";
    }
    ?>
</div>



<?php
include("footer.php");
?>