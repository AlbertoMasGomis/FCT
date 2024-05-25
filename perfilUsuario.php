<?php
include("header.php");


if ($_SESSION['rol'] != "user") {
    header("Location: index.php");
    exit();
}
// Obtener datos del usuario actual
$id_usuario = $_SESSION['usuario_id'];
$conexion = conectarBD();
$sql = "SELECT * FROM usuarios WHERE ID = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
} else {
    // Manejar el caso de que no se encuentre el usuario
    // Redirigir o mostrar un mensaje de error
}

// Manejar la actualización de datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar"])) {
    // Validar y procesar los datos enviados
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    // Aquí debes realizar las validaciones necesarias y el procesamiento de los datos,
    // como verificar que el correo sea válido, que la contraseña cumpla con ciertos criterios, etc.

    // Luego, realizar la actualización en la base de datos
    $sql_update = "UPDATE usuarios SET Nombre = ?, CorreoElectronico = ?, Usuario = ?, Contraseña = ? WHERE ID = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $nombre, $correo, $usuario, $contrasena, $id_usuario);
    $stmt_update->execute();

    // Verificar si la actualización fue exitosa
    if ($stmt_update->affected_rows > 0) {
        // Actualización exitosa
        $mensaje = "Los datos se han actualizado correctamente.";
    } else {
        // Error al actualizar
        $mensaje = "Error al actualizar los datos. Por favor, inténtalo de nuevo.";
    }
}
?>



<?php if (isset($mensaje)) : ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>
<div id="contenedor-form">
<h1 class="texto-centrado">Datos usuario</h1>
    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['Nombre']; ?>" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" value="<?php echo $usuario['CorreoElectronico']; ?>" required>

            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo $usuario['Usuario']; ?>" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit" name="actualizar" value="Actualizar Datos">Actualizar datos </button>
        </form>
    </div>
</div>

<?php
include("footer.php");
?>