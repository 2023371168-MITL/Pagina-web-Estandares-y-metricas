<?php

session_start();

/* eliminar todas las variables de sesion */
session_unset();

/* destruir sesion */
session_destroy();

/* redirigir al login */
header("Location: /mi_proyecto/login.php");
exit;

?>