<?php
include("../header.php");
session_start();
if (!isset($_SESSION['rol'])) {
    header("location: login.php");
} else {
    $_SESSION = [];
    session_destroy();
    header("Location: ../login.php");
    exit;
}
?>
<?php
include("../footer.php");

?>