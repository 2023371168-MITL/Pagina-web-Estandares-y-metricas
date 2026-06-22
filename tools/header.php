<?php

/* INICIAR SESION SOLO SI NO ESTA INICIADA */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* PROTEGER EL SISTEMA */
if (!isset($_SESSION['usuario'])) {
    header("Location: /mi_proyecto/login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Panel Administrativo | Misterios SA</title>

<!-- Fuente -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">

<!-- OverlayScrollbars -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css">

<!-- Bootstrap Icons -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<!-- Bootstrap -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="/mi_proyecto/css/adminlte.css">

</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">