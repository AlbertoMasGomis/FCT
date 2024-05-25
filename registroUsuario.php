<?php
include ("header.php");

function validarFormulario()
{
    $errores = array();

    if (empty($_POST['nombre'])) {
        $errores[] = "Nombre vacío";
    } else {
        //Comprobar que el nombre no sea mayor a 50 caracteres ni contenga caracteres especiales
        if (!preg_match("/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]{1,50}$/", $_POST["nombre"])) {
            $errores[] = "El nombre debe tener entre 1 y 50 caracteres y solo puede contener letras y espacios.";
        }
    }

    if (empty($_POST['contrasena1']) || empty($_POST['contrasena2'])) {
        $errores[] = "Contraseña vacía o repetida.";
    } elseif ($_POST['contrasena1'] !== $_POST['contrasena2']) {
        $errores[] = "Las contraseñas no coinciden.";
    } elseif (strlen($_POST['contrasena1']) < 8) {
        $errores[] = "Contraseña demasiado corta: debe de tener al menos 8 caracteres.";
    }

    return $errores;
}

$errores = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = validarFormulario();

    if (empty($errores)) {
        $tipo = 'Junior';
        $nombre = $_POST["nombre"];
        $email = $_POST['mail'];
        $usuarioReg = $_POST["usuario"];
        $pass1 = $_POST["contrasena1"];

        $conexion = conectarBD();



        // Comprobación de si el correo ya existe
        $sql_check_email = "SELECT COUNT(*) as count FROM usuarios WHERE correoelectronico = ?";
        $stmt_check_email = $conexion->prepare($sql_check_email);
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $resultado = $stmt_check_email->get_result();
        $row = $resultado->fetch_assoc();

        if ($row['count'] > 0) {
            $errores[] = "El correo electrónico que ha introducido ya está registrado.";
        } else {
            $sql = "INSERT INTO usuarios (tipo, nombre, correoelectronico, usuario, contraseña) VALUES (?, ?, ?, ?, ?)";
            $registrar = $conexion->prepare($sql);
            $registrar->bind_param("sssss", $tipo, $nombre, $email, $usuarioReg, $pass1);
            $registrar->execute();

            if ($registrar) {
                echo "Usuario registrado con éxito";
                header("Location: index.php");
                exit();
            }
        }
    }
}

?>
<h1 class="texto-centrado">Registro de usuario</h1>

<div class="form-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre"><br><br>
        <label for="email">Email:</label>
        <input type="email" name="mail" id="mail"><br><br>
        <label for="">Usuario: </label>
        <input type="text" name="usuario"><br><br>
        <label for="">Contraseña: </label>
        <input type="password" name="contrasena1"><br><br>
        <label for="">Repetir Contraseña: </label>
        <input type="password" name="contrasena2"><br><br>

        <?php if (!empty($errores)): ?>
            <div class="error-message" style="color:red" id="mensaje">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="botones">
        <button type="submit">Registrarse</button>
        <button type="submit"><a href="registroEmpresa.php">Quiero registrarme como empresa</a></button>
        </div>
        

    </form>
</div>

<?php include ("footer.php"); ?>