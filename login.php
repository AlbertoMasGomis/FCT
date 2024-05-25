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
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuarioLogin'";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuarioBD = mysqli_fetch_assoc($resultado);
            if ($usuarioLogin == $usuarioBD['Usuario'] && $passwordLogin == $usuarioBD['Contraseña']) {
                $_SESSION['usuario_id'] = $usuarioBD['ID'];
                
                // Verificar si el usuario es administrador
                if ($usuarioLogin == 'admin' && $passwordLogin == 'admin123') {
                    $_SESSION['rol'] = 'admin'; // Asignar el rol de administrador
                } else {
                    $_SESSION['rol'] = "user";
                }
                
                header("Location: index.php");
                exit;
            } else {
                $errores[] = $usuarioLogin . $passwordLogin . $usuarioBD['Usuario'] . $usuarioBD['Contraseña'];
                $errores[] = "Credenciales incorrectas, vuelva a introducir datos";
            }
        } else {
            $errores[] = "Usuario no encontrado";
        }

        cerrarBD($conexion);
    }
}

?>

<?php if (!empty($errores)) : ?>
        <div class="error-message" id="mensaje">
            <?php foreach ($errores as $error) : ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<!---Login--->
<div id="contenedor-form">
    <h1 class="texto-centrado">Login usuario</h1>
    <div class="form-container">
        <form action="login.php" method="post">
            <input type="text" name="usuario" id="usuario" placeholder="usuario">
            <input type="password" name="password" id="password" placeholder="password">
            <button type="submit" name="entrar" id="entrar">Iniciar sesión </button>
        </form>
    </div>
    
    <a href="loginempresa.php">Soy una empresa, no usuario</a>
</div>





<?php
include("footer.php");
?>