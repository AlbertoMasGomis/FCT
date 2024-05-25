<?php
include ("header.php");
?>

<?php
//validadores


// Verificar si el usuario está logeado como empresa
if ($_SESSION['rol'] != "empresa") {
    header("Location: index.php");
    exit();
}

// Obtener datos de la empresa actual
$id_empresa = $_SESSION['empresa_id'];
$conexion = conectarBD();
$sql = "SELECT * FROM empresa WHERE ID = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $empresa = $resultado->fetch_assoc();
} else {
    // Manejar el caso de que no se encuentre la empresa
    // Redirigir o mostrar un mensaje de error
}

// Manejar la actualización de datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar"])) {
    // Validar y procesar los datos enviados
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $descripcion = $_POST["descripcion"];

    // Aquí debes realizar las validaciones necesarias y el procesamiento de los datos,
    // como verificar que el correo sea válido, que la contraseña cumpla con ciertos criterios, etc.

    // Luego, realizar la actualización en la base de datos
    $sql_update = "UPDATE empresa SET Nombre = ?, CorreoElectronico = ?, Usuario = ?, Contraseña = ?, Descripcion = ? WHERE ID = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("sssssi", $nombre, $correo, $usuario, $contrasena, $descripcion, $id_empresa);
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




<?php if (isset($mensaje)): ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>
<div class="containerBtn">
<button id="verPerfilBtn">Ver Perfil</button>

</div>

<div id="contenedor-form" style="display:none">
    <h1 class="texto-centrado">Datos usuario</h1>
    <div class="form-container">
        <form id="userForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $empresa['Nombre']; ?>" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" value="<?php echo $empresa['CorreoElectronico']; ?>" required>

            <label for="usuario">Usuario (más de 3 letras):</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo $empresa['Usuario']; ?>" pattern=".{4,}"
                title="El usuario debe tener más de 3 letras" required>

            <label for="contrasena">Contraseña (mínimo 5 caracteres y al menos un número):</label>
            <input type="password" id="contrasena" name="contrasena" pattern="(?=.*\d).{5,}"
                title="La contraseña debe tener al menos 5 caracteres y al menos un número" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required><?php echo $empresa['Descripcion']; ?></textarea>

            <button type="submit" name="actualizar" value="Actualizar Datos">Actualizar datos </button>
        </form>


    </div>
</div>
<script>
    // Obtener referencia al botón "Ver Perfil" y al contenedor-form
    const verPerfilBtn = document.getElementById('verPerfilBtn');
    const contenedorForm = document.getElementById('contenedor-form');

    // Agregar un evento de clic al botón
    verPerfilBtn.addEventListener('click', function() {
        // Mostrar el contenedor-form cambiando su estilo de visualización a "block"
        if (contenedorForm.style.display === 'none') {
            contenedorForm.style.display = 'block';
        } else {
            contenedorForm.style.display = 'none';
        }    });
</script>



<?php
include ("footer.php");
?>