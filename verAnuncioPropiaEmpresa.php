<?php
include("header.php");

// Verificar si el usuario está logeado como empresa
if ($_SESSION['rol'] != "empresa") {
    header("Location: index.php");
    exit();
}

// Obtener el ID de la empresa actual
$id_empresa = $_SESSION['empresa_id'];

// Consultar las ofertas de empleo publicadas por la empresa
$conexion = conectarBD();
$sql_ofertas = "SELECT * FROM empleos WHERE IDEmpresa = ?";
$stmt_ofertas = $conexion->prepare($sql_ofertas);
$stmt_ofertas->bind_param("i", $id_empresa);
$stmt_ofertas->execute();
$resultado_ofertas = $stmt_ofertas->get_result();

?>



<h2>Ofertas de Empleo Publicadas</h2>

<?php if ($resultado_ofertas->num_rows > 0) : ?>
    <?php while ($oferta = $resultado_ofertas->fetch_assoc()) : ?>
        <div class="oferta">
            <h3><?php echo $oferta['Titulo']; ?></h3>
            <p><?php echo $oferta['Descripcion']; ?></p>
            <!-- Otros detalles de la oferta de empleo según sea necesario -->

             <!-- Formulario para borrar el anuncio -->
             <form class="delete-form" action="borrarOferta.php" method="post">
                <input type="hidden" name="id_oferta" value="<?php echo $oferta['ID']; ?>">
                <button type="submit" name="borrar_oferta">Borrar</button>
            </form>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <p>No se encontraron ofertas de empleo publicadas por esta empresa.</p>
<?php endif; ?>

<?php
include("footer.php");
?>