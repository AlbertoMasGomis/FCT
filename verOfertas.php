<?php
include ("header.php");


if ($_SESSION['rol'] != "user") {
    header("Location: index.php");
    exit();
}

/* POSTULACION */
if (isset($_POST['postular'])) {
    postular();
}
function postular()
{
    $id_usuario = $_SESSION['usuario_id'];


    // Obtener el ID del empleo al que se está postulando
    $id_empleo = $_POST['id_empleo']; // Supongamos que tienes una función para obtener el ID del empleo

    // Verificar que se haya obtenido el ID del empleo correctamente
    if ($id_empleo !== null) {
        // Insertar la postulación en la tabla de Postulaciones
        $conexion = conectarBD();
        $fecha_postulacion = date("Y-m-d");
        if (empty($_FILES['cv']['name'])) {?>
        <script>
                $(document).ready(function () { $("#faltaCV").modal("show"); });

        </script>
                <?php
            return; // No procesar la postulación si no se adjuntó un archivo CV
        }
        $cv_contenido = file_get_contents($_FILES['cv']['tmp_name']);

        $sql_insert_postulacion = "INSERT INTO Postulaciones (IDEmpleo, IDUsuario, FechaPostulacion, ArchivoPDF) VALUES (?, ?, ?, ?)";
        $stmt_insert_postulacion = $conexion->prepare($sql_insert_postulacion);
        $stmt_insert_postulacion->bind_param("iiss", $id_empleo, $id_usuario, $fecha_postulacion, $cv_contenido);
        $stmt_insert_postulacion->execute();

        // Verificar si la inserción fue exitosa
        if ($stmt_insert_postulacion->affected_rows > 0) {
            ?>
            <script>
                $(document).ready(function () { $("#siPostulacion").modal("show"); });
            </script>
            <?php
            // Después de la inserción exitosa en la base de datos

        } else {
            echo "Error al registrar la postulación.";
        }
    } else {
        echo "No se pudo obtener el ID del empleo.";
    }
}


function obtenerCategorias()
{
    $conexion = conectarBD();
    $sql = "SELECT Nombre FROM categorias";
    $resultado = $conexion->query($sql);

    $categorias = array();
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $categorias[] = $row["Nombre"];
        }
    }
    return $categorias;
}

// Función para obtener todas las ofertas de empleo
function obtenerOfertasEmpleo($categoria = null, $limit = 2, $offset = 0)
{
    $conexion = conectarBD();
    $sql = "SELECT * FROM empleos";

    // Si se proporciona una categoría, filtrar por esa categoría
    if ($categoria) {
        $sql .= " WHERE Categoria = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $categoria);
        $stmt->execute();
        $resultado = $stmt->get_result();
    } else {
        $sql .= " LIMIT ? OFFSET ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $resultado = $stmt->get_result();
    }

    $ofertas = array();
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $ofertas[] = $row;
        }
    }
    return $ofertas;
}

// Función para obtener el número total de ofertas de empleo
function obtenerTotalOfertas($categoria = null)
{
    $conexion = conectarBD();
    $sql = "SELECT COUNT(*) as total FROM empleos";
    if ($categoria) {
        $sql .= " WHERE Categoria = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $categoria);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();
        return $row['total'];
    } else {
        $resultado = $conexion->query($sql);
        $row = $resultado->fetch_assoc();
        return $row['total'];
    }
}

// Obtener todas las categorías disponibles
$categorias = obtenerCategorias();

// Obtener el número de página actual
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 2; // Número de ofertas por página
$offset = ($page - 1) * $limit;

// Si se envió un formulario de filtro, obtener las ofertas de empleo filtradas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["filtrar_categoria"])) {
    $categoria_seleccionada = $_POST["categoria"];
    $total_ofertas = obtenerTotalOfertas($categoria_seleccionada);
    $ofertas_empleo = obtenerOfertasEmpleo($categoria_seleccionada, $limit, $offset);
} else {
    // Si no se envió un formulario de filtro, obtener todas las ofertas de empleo
    $total_ofertas = obtenerTotalOfertas();
    $ofertas_empleo = obtenerOfertasEmpleo(null, $limit, $offset);
}

// Calcular el número total de páginas
$total_pages = ceil($total_ofertas / $limit);

?>


<style>
    .form-container {
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        text-align: center;
        background-color: #ece7e7;
        margin-top: 10px;
    }

    .form-container label {
        display: block;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .form-container select {
        margin-right: 10px;
    }

    .form-container input[type="text"],
    .form-container input[type="email"],
    .form-container input[type="password"],
    .form-container textarea {
        width: calc(100% - 10px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .error-message {
        margin-bottom: 10px;
        padding: 10px;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        color: #721c24;
        text-align: center;
    }

    .form-container button[type="submit"] {
        padding: 10px 20px;
        background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 0%, rgba(0, 212, 255, 1) 100%);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container button a {
        color: white;
        text-decoration: none;
    }

    .form-container button[type="submit"]:hover {
        background-color: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 0%, rgba(0, 212, 255, 1) 100%);
    }

    .oferta-empleo {
        background-color: #ece7e7;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 2px solid blue;
        width: 80%;
        margin: 0 auto;
        margin-bottom: 10px;
    }

    .oferta-empleo h3 {
        margin-top: 0;
    }

    p {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    button {
        margin-top: 10px;
    }
</style>

<!-- Formulario de filtro por categoría -->
<div id="contenedor-form">
    <h1 class="texto-centrado">Ver Ofertas</h1>
    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="categoria">Filtrar por categoría:</label>
            <select id="categoria" name="categoria">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria; ?>"><?php echo $categoria; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="filtrar_categoria">Filtrar</button>
        </form>
    </div>
</div>

<!-- Mostrar las ofertas de empleo -->
<?php if (!empty($ofertas_empleo)): ?>
    <ul>
        <?php foreach ($ofertas_empleo as $oferta): ?>
            <div class="oferta-empleo">
                <h3><?php echo $oferta['Titulo']; ?></h3>
                <p><?php echo $oferta['Descripcion']; ?></p>
                <p>Categoría: <?php echo $oferta['Categoria']; ?></p>

                <!-- Formulario para postularse a la oferta de empleo -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                    enctype="multipart/form-data">
                    <!-- Campo oculto para la ID de la oferta de empleo -->
                    <input type="hidden" name="id_empleo" value="<?php echo $oferta['ID']; ?>">

                    <!-- Campo para subir el CV -->
                    <label for="cv">Enviar CV:</label>
                    <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx">
                    <br>

                    <button class="boton-m" type="submit" name="postular">Postular</button>
                </form>
            </div>
        <?php endforeach; ?>
    </ul>
    <!-- Paginación -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

<?php else: ?>

    <?php if (empty($ofertas_empleo)): ?>
        <script>
            $(document).ready(function () {
                $('#noOfertasModal').modal('show');
            });
        </script>
    <?php endif; ?>


    <!-- Modal de No se encontraron ofertas de empleo -->
    <div class="modal fade" id="noOfertasModal" tabindex="-1" role="dialog" aria-labelledby="noOfertasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noOfertasModalLabel">Mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    No se encontraron ofertas de empleo.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    

<?php endif; ?>
<div class="modal fade" id="siPostulacion" tabindex="-1" role="dialog" aria-labelledby="noOfertasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noOfertasModalLabel">Mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Postulación realizada con éxito
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="faltaCV" tabindex="-1" role="dialog" aria-labelledby="noOfertasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noOfertasModalLabel">Mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Es requisito que subas un CV
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
<?php
include ("footer.php");
?>