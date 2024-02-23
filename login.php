<?php
session_start();
include 'config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizar y validar los datos del formulario
    $cedula = filter_var($_POST['cedula'], FILTER_SANITIZE_SPECIAL_CHARS);
    $contrasena = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);

    // Validar si la cédula y la contraseña cumplen con los requisitos necesarios
    if (empty($cedula) || empty($contrasena)) {
        $error = "Por favor, ingrese tanto la cédula como la contraseña.";
    } else {
        // Verificar credenciales usando una sentencia preparada
        $query = "SELECT * FROM empleados WHERE cedula = ?";
        $stmt = $con->prepare($query);
        $stmt->execute([$cedula]);

        // Comprobar si se encontraron filas
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar la contraseña con password_verify
            if (password_verify($contrasena, $row['contraseña'])) {
                // Generar un nuevo token CSRF
                $nuevo_token_csrf = bin2hex(random_bytes(32));

                // Actualizar el token CSRF en la base de datos
                $query_update_token = "UPDATE empleados SET token = ? WHERE id_empleado = ?";
                $stmt_update_token = $con->prepare($query_update_token);
                $stmt_update_token->execute([$nuevo_token_csrf, $row['id_empleado']]);

                // Almacenar el token CSRF en la sesión
                $_SESSION['token_csrf'] = $nuevo_token_csrf;

                // Iniciar sesión
                $_SESSION['id_empleado'] = $row['id_empleado'];
                $_SESSION['username'] = $row['nombre'] . ' ' . $row['apellido'];
                $_SESSION['token_csrf'] = $nuevo_token_csrf;

                // Devolver el token CSRF en la respuesta
                echo json_encode(['token_csrf' => $nuevo_token_csrf]);

                header('Location: index.php');
                exit();
            } else {
                $error = "Credenciales incorrectas";
            }
        } else {
            $error = "Credenciales incorrectas";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Met Recicla- Inicio de Sesion</title>
    <!-- Incluye los estilos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados */
        body {
            background-color: #4CAF50;
            /* Color de fondo verde mate */
            margin: 0;
            /* Elimina los márgenes predeterminados */
            padding: 0;
            /* Elimina los rellenos predeterminados */
            height: 100vh;
            /* Altura del 100% de la ventana */
            display: flex;
            /* Utiliza el modelo de caja flexible */
            align-items: center;
            /* Centra verticalmente el contenido */
            justify-content: center;
            /* Centra horizontalmente el contenido */
        }

        .custom-container {
            background-color: #fff;
            /* Color de fondo blanco */
            border-radius: 10px;
            /* Esquinas redondeadas */
            padding: 20px;
            /* Espaciado interno */
            max-width: 400px;
            /* Ancho máximo */
            width: 100%;
            /* Ancho del 100% en dispositivos pequeños */
            text-align: center;
            /* Centra el texto */
        }

        .custom-container img {
            max-height: 150px;
            /* Ajusta la altura máxima de la imagen */
            width: auto;
            /* Para mantener la proporción de la imagen */
            margin-bottom: 20px;
            /* Espaciado inferior */
        }

        .custom-container h2 {
            color: #333;
            /* Color de texto oscuro */
        }

        .form-control {
            margin-bottom: 15px;
            /* Espaciado inferior para los campos de formulario */
        }

        @media (max-width: 767px) {
            .custom-container {
                margin-top: 20px;
                margin-left: 20px;
                margin-right: 20px;
                margin-bottom: auto;
            }
        }

        @media (max-width: 576px) {
            .custom-container {
                margin-top: 20px;
                margin-left: 20px;
                margin-right: 20px;
                margin-bottom: auto;
            }
        }
    </style>
</head>

<body>

    <div class="container custom-container mt-4">
        <!-- Imagen arriba -->
        <img src="img/logo.png" class="img-fluid" alt="Logo">

        <!-- Texto "Met Recicla" -->
        <h2>Met Recicla</h2>

        <!-- Divisor -->
        <hr>
        <?php if (isset($error)) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        } ?>
        <!-- Formulario de inicio de sesión -->
        <form method="post" action="">
            <div class="mb-3">
                <label for="cedula" class="form-label">Cédula</label>
                <input type="number" name="cedula" placeholder="Ingrese su numero de cedula sin puntos." class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" name="password" placeholder="Ingrese su contraseña" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Iniciar Sesión</button>
        </form>
    </div>

    <!-- Incluye los scripts de Bootstrap (jQuery y Popper.js son necesarios) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>