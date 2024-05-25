<?php
include ("./bbdd/bd.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="web.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>


<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">
                <div id="logo"></div>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="juniors.php">Juniors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="empresas.php">Empresas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nosotros.php">Nosotros</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION["rol"])): ?>
                        <?php if ($_SESSION["rol"] == "user"): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="perfilUsuario.php">Hola Usuario</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="verOfertas.php">Ver ofertas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./bbdd/logout.php">Cerrar Sesión</a>
                            </li>
                        <?php elseif ($_SESSION["rol"] == "empresa"): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="perfilEmpresa.php">Hola Empresa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="anuncioEmpleo.php">Publicar empleo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="verPostulaciones.php">Ver mis publicaciones</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./bbdd/logout.php">Cerrar Sesión</a>
                            </li>
                        <?php elseif ($_SESSION["rol"] == "admin"): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="panel.php">Panel de Administrador</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./bbdd/logout.php">Cerrar Sesión</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Inicio sesión</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="loginEmpresa.php">Soy Empresa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="registro.php">Regístrate</a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Inicio sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="loginEmpresa.php">Soy Empresa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registroUsuario.php">Regístrate</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>