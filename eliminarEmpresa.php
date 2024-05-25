<?php
include("header.php");

if ($_SESSION['rol'] != "admin") {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_empresa = $_GET['id'];

    // Realizar la consulta para eliminar la empresa
    $conexion = conectarBD();
    $sql = "DELETE FROM empresas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_empresa);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Empresa eliminada correctamente
        header("Location: panel.php");
        exit();
    } else {
        // Error al eliminar la empresa
        echo "Error al eliminar la empresa.";
    }

    cerrarBD($conexion);
}
?>
