<?php
$ruta_sessioncheck = __DIR__ . '/../config/sessioncheck.php';
include_once($ruta_sessioncheck);
?>
<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <!-- Agrega estas líneas -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



    <!-- Fin de las líneas a agregar -->
    <style>
        body {
            padding-top: 56px;
            /* Altura del navbar fijo */
        }
    </style>

</head>

<body>
    <header>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="/mr/index.php">Met Recicla</a>
                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="/mr/index.php" aria-current="page">Inicio
                                <span class="visually-hidden">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mr/vistas/compras.php">Compras</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mr/vistas/ventas.php">Ventas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mr/vistas/gastos.php">Gastos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Paginas</a>
                            <div class="dropdown-menu" aria-labelledby="dropdownId">
                                <a class="dropdown-item" href="/mr/vistas/chatarra.php">Chatarras</a>
                                <a class="dropdown-item" href="/mr/vistas/proveedores.php">Proveedores</a>
                                <a class="dropdown-item" href="/mr/vistas/empleados.php">Empleados</a>
                                <a class="dropdown-item" href="/mr/vistas/locales.php">Locales Venta</a>
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto"> <!-- Añade esta línea -->
                        <li class="nav-item" id="bienvenidaContainer"></li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mr/logout.php"> | Cerrar Sesion</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>

        <script src="/mr/js/gn.js"></script>
        <style>
            /* Agrega estilos CSS personalizados */
            .navbar-nav {
                display: flex;
                align-items: center;
            }

            #bienvenidaContainer {
                margin-left: auto;
                /* Esto colocará el elemento al extremo derecho del contenedor flex */
                color: white;
                /* Establece el color de fuente igual al de otros elementos de navegación */
            }
        </style>

    </header>
    <main>
        <Br />