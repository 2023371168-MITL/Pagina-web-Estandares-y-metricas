<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/permisos.php');

$page = $_GET['page'] ?? 'dashboard';
$page = trim($page);

function cargarModuloSeguro($rutaModulo, $requiereAcceso = null)
{
    if ($requiereAcceso !== null && !tieneAcceso($requiereAcceso)) {
        include("modulos/home/index.php");
        exit;
    }

    if (file_exists($rutaModulo)) {
        include($rutaModulo);
    } else {
        include("modulos/home/index.php");
    }
    exit;
}

/* DASHBOARD / HOME */
if ($page == "dashboard" || $page == "home" || $page == "") {
    if (file_exists("modulos/home/index.php")) {
        include("modulos/home/index.php");
    } else {
        echo "No se encontró el módulo de inicio.";
    }
    exit;
}

/* USUARIOS */
if ($page == "usuarios") {
    cargarModuloSeguro("modulos/usuarios/index.php", "usuarios");
}

/* ROLES */
if ($page == "roles") {
    cargarModuloSeguro("modulos/roles/index.php", "roles");
}

/* CATEGORÍAS */
if ($page == "categorias") {
    cargarModuloSeguro("modulos/categorias/index.php", "categorias");
}

/* PRODUCTOS / SERVICIOS */
if ($page == "productos" || $page == "servicios") {
    cargarModuloSeguro("modulos/productos/index.php", "productos");
}

/* CITAS */
if ($page == "citas") {
    cargarModuloSeguro("modulos/citas/index.php", "citas");
}

/* REPORTES */
if ($page == "reportes") {
    cargarModuloSeguro("modulos/reportes/index.php", "reportes");
}

/* RECETAS */
if ($page == "recetas") {
    cargarModuloSeguro("modulos/recetas/index.php", "recetas");
}

/* FALLBACK */
include("modulos/home/index.php");
exit;
?>