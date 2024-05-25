<?php
include("header.php");

if ($_SESSION['rol'] != "admin") {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Realizar la consulta para eliminar el usuario
    $conexion = conectarBD();
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Usuario eliminado correctamente
        header("Location: panel.php");
        exit();
    } else {
        // Error al eliminar el usuario
        echo "Error al eliminar el usuario.";
    }

    cerrarBD($conexion);
}
?>
