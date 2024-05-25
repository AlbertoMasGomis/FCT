<?php
include("header.php");

/*REGISTRO EMPRESA*/
function validarFormulario()
{
    $errores = array();

    if (empty($_POST['nombreEmpresa'])) {
        $errores[] = "Nombre vacío";
    } else {
        //Comprobar que el nombre no sea mayor a 50 caracteres ni contenga caracteres especiales
        if (!preg_match("/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]{1,50}$/", $_POST["nombreEmpresa"])) {
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
        $nombreEmpresa = $_POST["nombreEmpresa"];
        $email = $_POST['mail'];
        $usuarioReg = $_POST["usuario"];
        $pass1 = $_POST["contrasena1"];
        $descripcion = $_POST['descripcion'];


        $conexion = conectarBD();
        $sql = "INSERT INTO empresa (Nombre, CorreoElectronico, usuario, contraseña, Descripcion) VALUES ('$nombreEmpresa','$email','$usuarioReg','$pass1','$descripcion')";
        $registrar = $conexion->prepare($sql);
        $resultado = $registrar->execute();
        /* prueba */
        if ($resultado) {
            $id_empresa = $conexion->insert_id;
            session_start();
            $_SESSION['usuario_id'] = $id_empresa;
            $_SESSION['tipo_usuario'] = 'Empresa';
            header("Location: index.php"); // Redirigir a la página para publicar empleo
            exit();
        } else {
            echo "Error al registrar la empresa.";
        }
    }
}

?>


<h1 class="texto-centrado">Registro de Empresa</h1>
<br>
<div class="form-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nombre">Nombre empresa:</label>
        <input type="text" name="nombreEmpresa" id="nombreEmpresa"><br><br>
        <label for="email">Email:</label>
        <input type="email" name="mail" id="mail"><br><br>
        <label for="">Usuario: </label>
        <input type="text" name="usuario"><br><br>
        <label for="">Contraseña: </label>
        <input type="password" name="contrasena1"><br><br>
        <label for="">Repetir Contraseña: </label>
        <input type="password" name="contrasena2"><br><br>
        <label for="descripción">Descripción breve de la empresa: </label>
        <textarea rows=4 cols=50 name="descripcion"></textarea><br><br>

        <?php if (!empty($errores)) : ?>
            <div class="error-message" id="mensaje">
                <?php foreach ($errores as $error) : ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <button type="submit">Registrarse</button>
    </form>
</div>



<?php
include("footer.php");
?>