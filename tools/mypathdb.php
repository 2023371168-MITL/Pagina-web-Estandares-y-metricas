<?php

/* CONFIGURACIÓN BASE DE DATOS */

$servername = "localhost";
$username   = "root";
$password   = "675871isabel";
$dbname     = "ventas";


/* CONEXIÓN */

$conn = new mysqli($servername, $username, $password, $dbname);


/* VERIFICAR CONEXIÓN */

if ($conn->connect_error) {

    die("Error de conexión a la base de datos: " . $conn->connect_error);

}


$conn->set_charset("utf8");

?>