<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$option = $_GET['option'] ?? '';

/* =========================================================
   INCLUIR ROL
========================================================= */
if ($option == "incluir") {

    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $accesos     = trim($_POST['accesos'] ?? '');
    $status      = intval($_POST['status'] ?? 1);

    if ($nombre == '' || $descripcion == '' || $accesos == '') {
        echo json_encode(["error" => 3]);
        exit;
    }

    if (!preg_match('/^[a-zA-ZñÑáéíóúü ]+$/', $nombre)) {
        echo json_encode(["error" => 3]);
        exit;
    }

    $nombre      = mysqli_real_escape_string($conn, $nombre);
    $descripcion = mysqli_real_escape_string($conn, $descripcion);
    $accesos     = mysqli_real_escape_string($conn, $accesos);

    $verificar = mysqli_query($conn, "SELECT id FROM roles WHERE nombre='$nombre' LIMIT 1");
    if (mysqli_num_rows($verificar) > 0) {
        echo json_encode(["error" => 1]);
        exit;
    }

    $sql = "INSERT INTO roles (nombre, descripcion, accesos, fecha, status)
            VALUES ('$nombre', '$descripcion', '$accesos', NOW(), '$status')";

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
   CONSULTAR ROL
========================================================= */
if ($option == "consultar") {

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $q = mysqli_query($conn, "SELECT * FROM roles WHERE id=$id");

    if (mysqli_num_rows($q) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $data = mysqli_fetch_assoc($q);

    echo json_encode([
        "exito"       => 1,
        "id"          => $data['id'],
        "nombre"      => $data['nombre'],
        "descripcion" => $data['descripcion'],
        "accesos"     => $data['accesos'],
        "status"      => $data['status']
    ]);

    exit;
}


/* =========================================================
   MODIFICAR ROL
========================================================= */
if ($option == "modificar") {

    $id          = intval($_POST['id'] ?? 0);
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $accesos     = trim($_POST['accesos'] ?? '');
    $status      = intval($_POST['status'] ?? 1);

    if ($id <= 0 || $nombre == '' || $descripcion == '' || $accesos == '') {
        echo json_encode(["error" => 3]);
        exit;
    }

    if (!preg_match('/^[a-zA-ZñÑáéíóúü ]+$/', $nombre)) {
        echo json_encode(["error" => 3]);
        exit;
    }

    $verificar = mysqli_query($conn, "SELECT id FROM roles WHERE id=$id");
    if (mysqli_num_rows($verificar) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $nombre      = mysqli_real_escape_string($conn, $nombre);
    $descripcion = mysqli_real_escape_string($conn, $descripcion);
    $accesos     = mysqli_real_escape_string($conn, $accesos);

    $duplicado = mysqli_query($conn, "SELECT id FROM roles WHERE nombre='$nombre' AND id!=$id LIMIT 1");
    if (mysqli_num_rows($duplicado) > 0) {
        echo json_encode(["error" => 1]);
        exit;
    }

    $sql = "UPDATE roles SET
                nombre='$nombre',
                descripcion='$descripcion',
                accesos='$accesos',
                status='$status'
            WHERE id=$id";

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
   ELIMINAR ROL
========================================================= */
if ($option == "eliminar") {

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $verificar = mysqli_query($conn, "SELECT id FROM roles WHERE id=$id");
    if (mysqli_num_rows($verificar) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $sql = "UPDATE roles SET status=2 WHERE id=$id";

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