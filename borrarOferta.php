<?php
include("header.php");
?>

<?php
// Verificar si se recibió la solicitud de borrado de oferta
if (isset($_POST['borrar_oferta'])) {
    // Obtener el ID de la oferta a borrar
    $id_oferta = $_POST['id_oferta'];

    $conexion = conectarBD();

    $sql_eliminar_postulaciones = "DELETE FROM postulaciones WHERE IDEmpleo = ?";
    $stmt_eliminar_postulaciones = $conexion->prepare($sql_eliminar_postulaciones);
    $stmt_eliminar_postulaciones->bind_param("i", $id_oferta);

    if ($stmt_eliminar_postulaciones->execute()) {
        // Ahora podemos eliminar la oferta de empleo
        $sql_eliminar_oferta = "DELETE FROM empleos WHERE ID = ?";
        $stmt_eliminar_oferta = $conexion->prepare($sql_eliminar_oferta);
        $stmt_eliminar_oferta->bind_param("i", $id_oferta);

        if ($stmt_eliminar_oferta->execute()) {
            echo "La oferta de empleo se ha borrado correctamente.";
            header("Location: index.php");
            exit();
        } else {
            echo "Error al borrar la oferta de empleo: " . $conexion->error;
        }
    } else {
        echo "Error al borrar las postulaciones asociadas a la oferta de empleo: " . $conexion->error;
    }

    $conexion->close();
} else {
    header("Location: index.php");
    exit();
}
?>


<?php
include("footer.php");
?>