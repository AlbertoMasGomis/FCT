<?php
include("header.php");

$errores = [];

// Validar el formulario cuando se envía
if (isset($_POST['entrar'])) {
    if (empty($_POST['usuario']) || empty($_POST['password'])) {
        $errores[] = "Todos los campos son obligatorios.";
    } else {
        $usuarioLogin = $_POST["usuario"];
        $passwordLogin = $_POST["password"];

        $conexion = conectarBD();

        // Consulta SQL para buscar el usuario en la base de datos
        $sql = "SELECT * FROM empresa WHERE usuario = '$usuarioLogin'";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuarioBD = mysqli_fetch_assoc($resultado);
            if ($usuarioLogin == $usuarioBD['Usuario'] && $passwordLogin == $usuarioBD['Contraseña']) {
                $_SESSION['tipo_usuario'] = 'Empresa';
                $_SESSION['empresa_id'] = $usuarioBD['ID'];
                $_SESSION['usuario_id'] = $usuarioBD['ID'];
                $_SESSION['rol'] = "empresa";
                header("Location: index.php");
                exit;
            } else {
                $errores[] = "Credenciales incorrectas, vuelva a introducir datos";
            }
        } else {
            $errores[] = "Usuario no encontrado";
        }

        cerrarBD($conexion);
    }
}



?>
<div id="contenedor-form">
    <h1 class="texto-centrado">Login Empresa</h1>
    <div class="form-container">
        <form action="loginEmpresa.php" method="post">
            <input type="text" name="usuario" id="usuario" placeholder="usuario">
            <input type="password" name="password" id="password" placeholder="password">
            <button type="submit" name="entrar" id="entrar">Iniciar sesión </button>
        </form>
    </div>
    <?php if (!empty($errores)) : ?>
        <div class="error-message" id="mensaje">
            <?php foreach ($errores as $error) : ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <a href="login.php">Soy un usuario, no empresa</a>

</div>


<?php
include("footer.php");
?>