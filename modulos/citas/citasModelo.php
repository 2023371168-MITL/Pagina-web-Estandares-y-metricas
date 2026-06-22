<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$option = $_GET['option'] ?? '';

/* =========================================================
   OBTENER MÉDICOS POR ESPECIALIDAD
========================================================= */
if ($option == "obtenerMedicos") {

    $id_especialidad = intval($_GET['id_especialidad'] ?? 0);

    if ($id_especialidad <= 0) {
        echo json_encode([]);
        exit;
    }

    /*
        CORRECCIÓN:
        Ya NO se busca por usuarios.especialidad
        porque ese campo lo tienes en NULL en tus médicos.

        Se debe buscar por:
        usuarios.id_especialidad = categoria seleccionada
    */
    $sql = "SELECT u.id, u.nombre
            FROM usuarios u
            INNER JOIN roles r ON u.rolid = r.id
            WHERE u.status = 1
              AND (LOWER(r.nombre) = 'medico' OR LOWER(r.nombre) = 'médico')
              AND u.id_especialidad = '$id_especialidad'
            ORDER BY u.nombre ASC";

    $resultado = mysqli_query($conn, $sql);

    $medicos = [];

    if ($resultado) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            $medicos[] = [
                "id"     => $row['id'],
                "nombre" => $row['nombre']
            ];
        }
    }

    echo json_encode($medicos);
    exit;
}


/* =========================================================
   INCLUIR CITA
========================================================= */
if ($option == "incluir") {

    $rolSesion = strtolower(trim($_SESSION['rol'] ?? ''));
    $idSesion  = intval($_SESSION['id'] ?? 0);

    $id_paciente     = intval($_POST['id_paciente'] ?? 0);
    $id_especialidad = intval($_POST['id_especialidad'] ?? 0);
    $id_medico       = intval($_POST['id_medico'] ?? 0);
    $id_servicio     = intval($_POST['id_servicio'] ?? 0);
    $fecha_cita      = trim($_POST['fecha_cita'] ?? '');
    $hora_cita       = trim($_POST['hora_cita'] ?? '');
    $estado          = trim($_POST['estado'] ?? 'Pendiente');
    $observaciones   = trim($_POST['observaciones'] ?? '');

    /* Si es paciente, la cita queda a su nombre */
    if ($rolSesion == 'paciente') {
        $id_paciente = $idSesion;
    }

    if ($id_paciente <= 0 || $id_especialidad <= 0 || $id_medico <= 0 || $id_servicio <= 0 || $fecha_cita == '' || $hora_cita == '') {
        echo json_encode(["error" => 3]);
        exit;
    }

    $estado        = mysqli_real_escape_string($conn, $estado);
    $observaciones = mysqli_real_escape_string($conn, $observaciones);

    $sql = "INSERT INTO citas
            (id_paciente, id_especialidad, id_medico, id_servicio, fecha_cita, hora_cita, observaciones, estado, fecha_registro, status)
            VALUES
            ('$id_paciente', '$id_especialidad', '$id_medico', '$id_servicio', '$fecha_cita', '$hora_cita', '$observaciones', '$estado', NOW(), 1)";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["exito" => 1]);
    } else {
        echo json_encode([
            "error"   => 4,
            "mensaje" => mysqli_error($conn)
        ]);
    }

    exit;
}


/* =========================================================
   CONSULTAR PARA MODIFICAR
========================================================= */
if ($option == "modificarConsultar") {

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $rolSesion = strtolower(trim($_SESSION['rol'] ?? ''));
    $idSesion  = intval($_SESSION['id'] ?? 0);

    $whereExtra = "";

    // Paciente: solo sus citas
    if ($rolSesion == 'paciente') {
        $whereExtra = " AND id_paciente = $idSesion ";
    }

    // Médico: solo sus citas
    if ($rolSesion == 'medico' || $rolSesion == 'médico') {
        $whereExtra = " AND id_medico = $idSesion ";
    }

    $q = mysqli_query($conn, "SELECT * FROM citas WHERE id = $id $whereExtra LIMIT 1");

    if (!$q || mysqli_num_rows($q) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $data = mysqli_fetch_assoc($q);

    echo json_encode([
        "exito"           => 1,
        "id"              => $data['id'],
        "id_paciente"     => $data['id_paciente'],
        "id_especialidad" => $data['id_especialidad'],
        "id_medico"       => $data['id_medico'],
        "id_servicio"     => $data['id_servicio'],
        "fecha_cita"      => $data['fecha_cita'],
        "hora_cita"       => $data['hora_cita'],
        "observaciones"   => $data['observaciones'],
        "estado"          => $data['estado']
    ]);

    exit;
}


/* =========================================================
   MODIFICAR CITA
========================================================= */
if ($option == "modificar") {

    $rolSesion = strtolower(trim($_SESSION['rol'] ?? ''));
    $idSesion  = intval($_SESSION['id'] ?? 0);

    $id              = intval($_POST['id'] ?? 0);
    $id_paciente     = intval($_POST['id_paciente'] ?? 0);
    $id_especialidad = intval($_POST['id_especialidad'] ?? 0);
    $id_medico       = intval($_POST['id_medico'] ?? 0);
    $id_servicio     = intval($_POST['id_servicio'] ?? 0);
    $fecha_cita      = trim($_POST['fecha_cita'] ?? '');
    $hora_cita       = trim($_POST['hora_cita'] ?? '');
    $estado          = trim($_POST['estado'] ?? 'Pendiente');
    $observaciones   = trim($_POST['observaciones'] ?? '');

    if ($id <= 0 || $id_paciente <= 0 || $id_especialidad <= 0 || $id_medico <= 0 || $id_servicio <= 0 || $fecha_cita == '' || $hora_cita == '') {
        echo json_encode(["error" => 3]);
        exit;
    }

    if ($rolSesion == 'paciente') {
        $id_paciente = $idSesion;
    }

    $verificar = mysqli_query($conn, "SELECT * FROM citas WHERE id = $id LIMIT 1");

    if (!$verificar || mysqli_num_rows($verificar) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $cita = mysqli_fetch_assoc($verificar);

    if ($rolSesion == 'paciente') {
        if (intval($cita['id_paciente']) !== $idSesion) {
            echo json_encode(["error" => 4, "mensaje" => "No puedes modificar esta cita"]);
            exit;
        }

        $estadoActual = strtolower(trim($cita['estado']));
        if ($estadoActual == 'atendida' || $estadoActual == 'cancelada') {
            echo json_encode(["error" => 4, "mensaje" => "No se puede modificar una cita atendida o cancelada"]);
            exit;
        }

        $estado = $cita['estado'];
    }

    if ($rolSesion == 'medico' || $rolSesion == 'médico') {
        if (intval($cita['id_medico']) !== $idSesion) {
            echo json_encode(["error" => 4, "mensaje" => "No puedes modificar esta cita"]);
            exit;
        }
    }

    $estado        = mysqli_real_escape_string($conn, $estado);
    $observaciones = mysqli_real_escape_string($conn, $observaciones);

    $sql = "UPDATE citas SET
                id_paciente     = '$id_paciente',
                id_especialidad = '$id_especialidad',
                id_medico       = '$id_medico',
                id_servicio     = '$id_servicio',
                fecha_cita      = '$fecha_cita',
                hora_cita       = '$hora_cita',
                observaciones   = '$observaciones',
                estado          = '$estado'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["exito" => 1]);
    } else {
        echo json_encode([
            "error"   => 4,
            "mensaje" => mysqli_error($conn)
        ]);
    }

    exit;
}


/* =========================================================
   CANCELAR / ELIMINAR CITA
========================================================= */
if ($option == "eliminar") {

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => 1]);
        exit;
    }

    $rolSesion = strtolower(trim($_SESSION['rol'] ?? ''));
    $idSesion  = intval($_SESSION['id'] ?? 0);

    $verificar = mysqli_query($conn, "SELECT * FROM citas WHERE id = $id LIMIT 1");

    if (!$verificar || mysqli_num_rows($verificar) == 0) {
        echo json_encode(["error" => 1]);
        exit;
    }

    $cita = mysqli_fetch_assoc($verificar);

    if ($rolSesion == 'paciente' && intval($cita['id_paciente']) !== $idSesion) {
        echo json_encode(["error" => 4, "mensaje" => "No puedes cancelar esta cita"]);
        exit;
    }

    if (($rolSesion == 'medico' || $rolSesion == 'médico') && intval($cita['id_medico']) !== $idSesion) {
        echo json_encode(["error" => 4, "mensaje" => "No puedes cancelar esta cita"]);
        exit;
    }

    $sql = "UPDATE citas 
            SET status = 2, estado = 'Cancelada'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["exito" => 1]);
    } else {
        echo json_encode(["error" => 4]);
    }

    exit;
}


/* =========================================================
   MARCAR CITA COMO ATENDIDA
========================================================= */
if ($option == "marcarAtendida") {

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => 2, "mensaje" => "Cita inválida"]);
        exit;
    }

    $rolSesion = strtolower(trim($_SESSION['rol'] ?? ''));
    $idSesion  = intval($_SESSION['id'] ?? 0);

    $q = mysqli_query($conn, "SELECT * FROM citas WHERE id = $id LIMIT 1");

    if (!$q || mysqli_num_rows($q) == 0) {
        echo json_encode(["error" => 2, "mensaje" => "La cita no existe"]);
        exit;
    }

    $cita = mysqli_fetch_assoc($q);

    $esAdmin  = ($rolSesion == 'administrador');
    $esMedico = ($rolSesion == 'medico' || $rolSesion == 'médico');

    if (!$esAdmin && !$esMedico) {
        echo json_encode(["error" => 3, "mensaje" => "No tienes permisos"]);
        exit;
    }

    if ($esMedico && intval($cita['id_medico']) !== $idSesion) {
        echo json_encode(["error" => 3, "mensaje" => "Solo puedes atender tus propias citas"]);
        exit;
    }

    $estadoActual = strtolower(trim($cita['estado']));

    if ($estadoActual == 'atendida') {
        echo json_encode(["error" => 4, "mensaje" => "La cita ya está atendida"]);
        exit;
    }

    if ($estadoActual == 'cancelada') {
        echo json_encode(["error" => 5, "mensaje" => "No se puede atender una cita cancelada"]);
        exit;
    }

    $update = "UPDATE citas
               SET estado = 'Atendida'
               WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        echo json_encode(["exito" => 1, "mensaje" => "Cita marcada como atendida"]);
    } else {
        echo json_encode([
            "error"   => 6,
            "mensaje" => mysqli_error($conn)
        ]);
    }

    exit;
}

mysqli_close($conn);
?>