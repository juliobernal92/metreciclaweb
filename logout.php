<?php
session_start();

// Limpia todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Borra la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirige a la página de inicio de sesión
header("Location: login.php");
exit();
?>