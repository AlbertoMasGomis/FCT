<?php
include("header.php");

if ($_SESSION['rol'] != "empresa") {
    header("Location: index.php");
    exit();
}

if (isset($_POST['ver_postulaciones'])) {
    // Obtener el ID del puesto de trabajo
    $puesto_id = $_POST['puesto_id'];

    // Consulta para obtener el ID del postulante asociado al puesto de trabajo
    $conexion = conectarBD();
    $sql = "SELECT IDUsuario FROM Postulaciones WHERE IDEmpleo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $puesto_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    echo "<div class='container-info'>
        <h1 class='texto-centrado'>Ver Ofertas</h1>
";
    // Verificar si se encontraron postulantes
    if ($resultado->num_rows > 0) {
        // Recorrer los resultados y mostrar la información de cada postulante
        while ($fila = $resultado->fetch_assoc()) {
            $postulante_id = $fila['IDUsuario'];
            // Consulta para obtener la información del postulante
            $sql_postulante = "SELECT * FROM usuarios WHERE id = ?";
            $stmt_postulante = $conexion->prepare($sql_postulante);
            $stmt_postulante->bind_param("i", $postulante_id);
            $stmt_postulante->execute();
            $resultado_postulante = $stmt_postulante->get_result();

            // Verificar si se encontró el postulante
            if ($resultado_postulante->num_rows > 0) {
                while ($postulante = $resultado_postulante->fetch_assoc()) {
                    echo "<div class='postulante-info'>";
                    echo "<h2>Información del postulante</h2>";
                    echo "<p><strong>ID del postulante:</strong> " . $postulante['ID'] . "</p>";
                    echo "<p><strong>Nombre:</strong> " . $postulante['Nombre'] . "</p>";
                    echo "<p><strong>Correo electrónico:</strong> " . $postulante['CorreoElectronico'] . "</p>";
                    // Mostrar más información según sea necesario
                    echo "</div>";
                }
            } else {
                echo "No se encontró información del postulante.";
            }
        }
    } else {
        ?>
        <script>
        $(document).ready(function(){
            $('#noOfertasModal').modal('show');
        });
    </script>
    <div class="modal fade" id="noOfertasModal" tabindex="-1" role="dialog" aria-labelledby="noOfertasModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noOfertasModalLabel">Mensaje informativo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                No se encontraron postulantes a este empleo.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
    <?php
        echo "<p class='center'>No se encontraron postulantes para este puesto de trabajo.</p>";
    }
} else {
    echo "No se recibió el ID del puesto de trabajo.";
}
echo "</div>" ;


include("footer.php");
