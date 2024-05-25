<?php

$servidor = "localhost";
$usuario = "root";
$password = "";
$bd = "proyectofct";

function conectarBD()
{
    global $servidor, $usuario, $password, $bd;

    $conexion =  mysqli_connect($servidor, $usuario, $password, $bd);

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    } else {
        return $conexion;
    }
}

function cerrarBD($conexion)
{

    $conexion->close();
}
