<?php
include("header.php");
if ($_SESSION['tipo_usuario'] !== 'Empresa') {
    header("Location: index.php"); // Redirigir a la página principal si no es una empresa
    exit();
}

$conexion = conectarBD();

// Lectura de las categorías
$sql_categorias = "SELECT Nombre FROM categorias";
$resultado_categorias = $conexion->query($sql_categorias);

// Verificar si se encontraron categorías
if ($resultado_categorias->num_rows > 0) {
    // Array para almacenar las categorías
    $categorias = array();

    // Recorrer los resultados y almacenar las categorías en el array
    while ($row = $resultado_categorias->fetch_assoc()) {
        $categorias[] = $row["Nombre"];
    }
}

// Cerrar la conexión a la base de datos
$conexion->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $id_empresa = $_SESSION['usuario_id'];
    $categoria = $_POST['categoria'];

    // Verificar si se encontraron categorías antes de insertar el empleo
    if (isset($categorias)) {
        $conexion = conectarBD();

        $sql = "INSERT INTO empleos (Titulo, Descripcion, Categoria, IDEmpresa) VALUES (?, ?, ?, ?)";
        $registrar_empleo = $conexion->prepare($sql);
        $registrar_empleo->bind_param("sssi", $titulo, $descripcion, $categoria, $id_empresa);
        $resultado = $registrar_empleo->execute();

        if ($resultado) {
            echo "El empleo se ha registrado correctamente.";
        } else {
            echo "Error al registrar el empleo.";
        }
    } else {
        echo "No se encontraron categorías.";
    }
}
?>

<div id="contenedor-form">
    <h1 class="texto-centrado prueba">Publicar empleo</h1>
    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="titulo">Título del Empleo:</label><br>
            <input type="text" id="titulo" name="titulo" required><br><br>

            <label for="descripcion">Descripción del Empleo:</label><br>
            <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>

            <label for="categoria">Categoría:</label><br>
            <select id="categoria" name="categoria">
                <?php foreach ($categorias as $categoria) : ?>
                    <option value="<?php echo $categoria; ?>"><?php echo $categoria; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <!-- Otros campos del formulario según sea necesario -->

            <button type="submit" value="Publicar Empleo">Publicar empleo </button>
    </div>
</div>
</form>
<?php
include("footer.php");
?>
</body>

</html>