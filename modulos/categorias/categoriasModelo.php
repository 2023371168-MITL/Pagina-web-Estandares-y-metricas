<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

header('Content-Type: application/json; charset=utf-8');

$option = $_GET['option'] ?? '';
$uploadDir = $_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/uploads/categorias/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* =========================================================
   FUNCION PARA SABER SI EXISTE UNA COLUMNA
========================================================= */
function existeColumna($conn, $tabla, $columna){
    $tabla = mysqli_real_escape_string($conn, $tabla);
    $columna = mysqli_real_escape_string($conn, $columna);

    $sql = "SHOW COLUMNS FROM `$tabla` LIKE '$columna'";
    $q = mysqli_query($conn, $sql);

    return ($q && mysqli_num_rows($q) > 0);
}

$tieneImagen = existeColumna($conn, "categorias", "imagen");
$tieneFecha  = existeColumna($conn, "categorias", "fecha");
$tieneStatus = existeColumna($conn, "categorias", "status");

/* =========================================================
   INCLUIR
========================================================= */
if ($option == "incluir") {

    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $status      = intval($_POST['status'] ?? 1);

    if ($nombre == '' || $descripcion == '') {
        echo json_encode([
            "error" => 1,
            "mensaje" => "Debe completar nombre y descripción"
        ]);
        exit;
    }

    $nombreDB      = mysqli_real_escape_string($conn, $nombre);
    $descripcionDB = mysqli_real_escape_string($conn, $descripcion);

    $verificar = mysqli_query($conn, "SELECT id FROM categorias WHERE nombre='$nombreDB' LIMIT 1");
    if ($verificar && mysqli_num_rows($verificar) > 0) {
        echo json_encode([
            "error" => 1,
            "mensaje" => "La especialidad ya existe"
        ]);
        exit;
    }

    $nombreImagen = "";

    if ($tieneImagen && isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $tmpName = $_FILES['imagen']['tmp_name'];
        $originalName = $_FILES['imagen']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $permitidas)) {
            echo json_encode([
                "error" => 1,
                "mensaje" => "Formato de imagen no permitido"
            ]);
            exit;
        }

        $nombreImagen = time() . "_" . preg_replace('/[^A-Za-z0-9_\.-]/', '_', $originalName);

        if (!move_uploaded_file($tmpName, $uploadDir . $nombreImagen)) {
            echo json_encode([
                "error" => 1,
                "mensaje" => "No se pudo guardar la imagen"
            ]);
            exit;
        }
    }

    $campos = ["nombre", "descripcion"];
    $valores = ["'$nombreDB'", "'$descripcionDB'"];

    if ($tieneImagen) {
        $campos[] = "imagen";
        $valores[] = "'$nombreImagen'";
    }

    if ($tieneFecha) {
        $campos[] = "fecha";
        $valores[] = "NOW()";
    }

    if ($tieneStatus) {
        $campos[] = "status";
        $valores[] = "'$status'";
    }

    $sql = "INSERT INTO categorias (" . implode(", ", $campos) . ")
            VALUES (" . implode(", ", $valores) . ")";

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            "exito" => 1,
            "mensaje" => "Especialidad registrada correctamente"
        ]);
    } else {
        echo json_encode([
            "error" => 1,
            "mensaje" => "No se pudo registrar la especialidad: " . mysqli_error($conn)
        ]);
    }

    exit;
}

/* =========================================================
   CONSULTAR
========================================================= */
if ($option == "consultar") {

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode([
            "error" => 1,
            "mensaje" => "ID inválido"
        ]);
        exit;
    }

    $q = mysqli_query($conn, "SELECT * FROM categorias WHERE id = $id LIMIT 1");

    if (!$q || mysqli_num_rows($q) == 0) {
        echo json_encode([
            "error" => 1,
            "mensaje" => "La especialidad no existe"
        ]);
        exit;
    }

    $data = mysqli_fetch_assoc($q);

    echo json_encode([
        "exito"       => 1,
        "id"          => $data['id'],
        "nombre"      => $data['nombre'] ?? '',
        "descripcion" => $data['descripcion'] ?? '',
        "imagen"      => ($tieneImagen ? ($data['imagen'] ?? '') : ''),
        "status"      => ($tieneStatus ? ($data['status'] ?? 1) : 1)
    ]);

    exit;
}

/* =========================================================
   MODIFICAR
========================================================= */
if ($option == "modificar") {

    $id          = intval($_POST['id'] ?? 0);
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $status      = intval($_POST['status'] ?? 1);

    if ($id <= 0 || $nombre == '' || $descripcion == '') {
        echo json_encode([
            "error" => 1,
            "mensaje" => "Debe completar nombre y descripción"
        ]);
        exit;
    }

    $nombreDB      = mysqli_real_escape_string($conn, $nombre);
    $descripcionDB = mysqli_real_escape_string($conn, $descripcion);

    $qActual = mysqli_query($conn, "SELECT * FROM categorias WHERE id = $id LIMIT 1");
    if (!$qActual || mysqli_num_rows($qActual) == 0) {
        echo json_encode([
            "error" => 1,
            "mensaje" => "La especialidad no existe"
        ]);
        exit;
    }

    $actual = mysqli_fetch_assoc($qActual);
    $nombreImagen = ($tieneImagen ? ($actual['imagen'] ?? '') : '');

    $duplicado = mysqli_query($conn, "SELECT id FROM categorias WHERE nombre='$nombreDB' AND id != $id LIMIT 1");
    if ($duplicado && mysqli_num_rows($duplicado) > 0) {
        echo json_encode([
            "error" => 1,
            "mensaje" => "Ya existe otra especialidad con ese nombre"
        ]);
        exit;
    }

    if ($tieneImagen && isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $tmpName = $_FILES['imagen']['tmp_name'];
        $originalName = $_FILES['imagen']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $permitidas)) {
            echo json_encode([
                "error" => 1,
                "mensaje" => "Formato de imagen no permitido"
            ]);
            exit;
        }

        $nuevoNombreImagen = time() . "_" . preg_replace('/[^A-Za-z0-9_\.-]/', '_', $originalName);

        if (!move_uploaded_file($tmpName, $uploadDir . $nuevoNombreImagen)) {
            echo json_encode([
                "error" => 1,
                "mensaje" => "No se pudo guardar la nueva imagen"
            ]);
            exit;
        }

        if (!empty($nombreImagen) && file_exists($uploadDir . $nombreImagen)) {
            @unlink($uploadDir . $nombreImagen);
        }

        $nombreImagen = $nuevoNombreImagen;
    }

    $sets = [];
    $sets[] = "nombre = '$nombreDB'";
    $sets[] = "descripcion = '$descripcionDB'";

    if ($tieneImagen) {
        $sets[] = "imagen = '$nombreImagen'";
    }

    if ($tieneStatus) {
        $sets[] = "status = '$status'";
    }

    $sql = "UPDATE categorias SET " . implode(", ", $sets) . " WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            "exito" => 1,
            "mensaje" => "Especialidad actualizada correctamente"
        ]);
    } else {
        echo json_encode([
            "error" => 1,
            "mensaje" => "No se pudo actualizar la especialidad: " . mysqli_error($conn)
        ]);
    }

    exit;
}

/* =========================================================
   ELIMINAR
========================================================= */
if ($option == "eliminar") {

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode([
            "error" => 1,
            "mensaje" => "ID inválido"
        ]);
        exit;
    }

    if ($tieneStatus) {
        $sql = "UPDATE categorias SET status = 2 WHERE id = $id";
    } else {
        $sql = "DELETE FROM categorias WHERE id = $id";
    }

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            "exito" => 1,
            "mensaje" => "Especialidad eliminada correctamente"
        ]);
    } else {
        echo json_encode([
            "error" => 1,
            "mensaje" => "No se pudo eliminar la especialidad: " . mysqli_error($conn)
        ]);
    }

    exit;
}

echo json_encode([
    "error" => 1,
    "mensaje" => "Opción no válida"
]);

mysqli_close($conn);
?>