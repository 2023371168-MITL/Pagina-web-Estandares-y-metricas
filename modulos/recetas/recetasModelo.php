<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$option = $_GET['option'] ?? '';

$rolSesion = strtolower(trim($_SESSION['rol'] ?? ''));
$idSesion  = intval($_SESSION['id'] ?? 0);

/* =========================================================
   INCLUIR RECETA
========================================================= */
if ($option == "incluir") {

    if ($rolSesion != 'medico' && $rolSesion != 'médico') {
        echo json_encode(["error" => 4, "mensaje" => "Solo los médicos pueden crear recetas"]);
        exit;
    }

    $id_paciente          = intval($_POST['id_paciente'] ?? 0);
    $diagnostico          = trim($_POST['diagnostico'] ?? '');
    $medicamentos         = trim($_POST['medicamentos'] ?? '');
    $indicaciones         = trim($_POST['indicaciones'] ?? '');
    $cedula_profesional   = trim($_POST['cedula_profesional'] ?? '');

    if ($id_paciente <= 0 || $diagnostico == '' || $medicamentos == '' || $indicaciones == '' || $cedula_profesional == '') {
        echo json_encode(["error" => 3]);
        exit;
    }

    $diagnostico        = mysqli_real_escape_string($conn, $diagnostico);
    $medicamentos       = mysqli_real_escape_string($conn, $medicamentos);
    $indicaciones       = mysqli_real_escape_string($conn, $indicaciones);
    $cedula_profesional = mysqli_real_escape_string($conn, $cedula_profesional);

    $sql = "INSERT INTO recetas
            (id_paciente, id_medico, diagnostico, medicamentos, indicaciones, cedula_profesional, fecha_receta, status)
            VALUES
            ('$id_paciente', '$idSesion', '$diagnostico', '$medicamentos', '$indicaciones', '$cedula_profesional', NOW(), 1)";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["exito" => 1]);
    } else {
        echo json_encode([
            "error" => 4,
            "mensaje" => mysqli_error($conn)
        ]);
    }

    exit;
}


/* =========================================================
   CONSULTAR RECETA
========================================================= */
if ($option == "consultar") {

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $whereExtra = "";

    if ($rolSesion == 'paciente') {
        $whereExtra = " AND id_paciente = $idSesion ";
    }

    if ($rolSesion == 'medico' || $rolSesion == 'médico') {
        $whereExtra = " AND id_medico = $idSesion ";
    }

    $q = mysqli_query($conn, "SELECT * FROM recetas WHERE id = $id AND status = 1 $whereExtra LIMIT 1");

    if (!$q || mysqli_num_rows($q) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $data = mysqli_fetch_assoc($q);

    echo json_encode([
        "exito"               => 1,
        "id"                  => $data['id'],
        "id_paciente"         => $data['id_paciente'],
        "diagnostico"         => $data['diagnostico'],
        "medicamentos"        => $data['medicamentos'],
        "indicaciones"        => $data['indicaciones'],
        "cedula_profesional"  => $data['cedula_profesional']
    ]);

    exit;
}


/* =========================================================
   MODIFICAR RECETA
========================================================= */
if ($option == "modificar") {

    if ($rolSesion != 'medico' && $rolSesion != 'médico') {
        echo json_encode(["error" => 4, "mensaje" => "Solo los médicos pueden modificar recetas"]);
        exit;
    }

    $id                   = intval($_POST['id'] ?? 0);
    $id_paciente          = intval($_POST['id_paciente'] ?? 0);
    $diagnostico          = trim($_POST['diagnostico'] ?? '');
    $medicamentos         = trim($_POST['medicamentos'] ?? '');
    $indicaciones         = trim($_POST['indicaciones'] ?? '');
    $cedula_profesional   = trim($_POST['cedula_profesional'] ?? '');

    if ($id <= 0 || $id_paciente <= 0 || $diagnostico == '' || $medicamentos == '' || $indicaciones == '' || $cedula_profesional == '') {
        echo json_encode(["error" => 3]);
        exit;
    }

    $verificar = mysqli_query($conn, "SELECT * FROM recetas WHERE id = $id AND id_medico = $idSesion AND status = 1 LIMIT 1");

    if (!$verificar || mysqli_num_rows($verificar) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $diagnostico        = mysqli_real_escape_string($conn, $diagnostico);
    $medicamentos       = mysqli_real_escape_string($conn, $medicamentos);
    $indicaciones       = mysqli_real_escape_string($conn, $indicaciones);
    $cedula_profesional = mysqli_real_escape_string($conn, $cedula_profesional);

    $sql = "UPDATE recetas SET
                id_paciente = '$id_paciente',
                diagnostico = '$diagnostico',
                medicamentos = '$medicamentos',
                indicaciones = '$indicaciones',
                cedula_profesional = '$cedula_profesional'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["exito" => 1]);
    } else {
        echo json_encode([
            "error" => 4,
            "mensaje" => mysqli_error($conn)
        ]);
    }

    exit;
}


/* =========================================================
   ELIMINAR RECETA
========================================================= */
if ($option == "eliminar") {

    if ($rolSesion != 'medico' && $rolSesion != 'médico') {
        echo json_encode(["error" => 4, "mensaje" => "Solo los médicos pueden eliminar recetas"]);
        exit;
    }

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $verificar = mysqli_query($conn, "SELECT * FROM recetas WHERE id = $id AND id_medico = $idSesion AND status = 1 LIMIT 1");

    if (!$verificar || mysqli_num_rows($verificar) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $sql = "UPDATE recetas SET status = 2 WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["exito" => 1]);
    } else {
        echo json_encode([
            "error" => 4,
            "mensaje" => mysqli_error($conn)
        ]);
    }

    exit;
}

mysqli_close($conn);
?>