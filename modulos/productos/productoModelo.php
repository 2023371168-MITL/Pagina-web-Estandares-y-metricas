<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$option = $_GET['option'] ?? '';

/* =========================================================
   INCLUIR SERVICIO MÉDICO
========================================================= */
if ($option == "incluir") {

    $nombre = trim($_POST['nombre'] ?? '');
    $marca  = trim($_POST['marca'] ?? '');
    $desc   = trim($_POST['descripcion'] ?? '');
    $stock  = intval($_POST['stock'] ?? 0);
    $cat    = intval($_POST['id_categoria'] ?? 0);
    $status = intval($_POST['status'] ?? 1);

    /* VALIDACIONES */
    if ($nombre == "" || $marca == "" || $desc == "" || $stock < 0 || $cat <= 0) {
        echo json_encode(["error" => 3]);
        exit;
    }

    /* ESCAPAR DATOS */
    $nombre = mysqli_real_escape_string($conn, $nombre);
    $marca  = mysqli_real_escape_string($conn, $marca);
    $desc   = mysqli_real_escape_string($conn, $desc);

    /* IMAGEN */
    $img = "";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {

        $dir = $_SERVER['DOCUMENT_ROOT']."/mi_proyecto/img/productos/";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg','jpeg','png','gif','webp'];

        if (!in_array($extension, $permitidas)) {
            echo json_encode([
                "error" => 4,
                "mensaje" => "Formato de imagen no permitido"
            ]);
            exit;
        }

        $img = time()."_servicio.".$extension;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $dir.$img)) {
            echo json_encode([
                "error" => 4,
                "mensaje" => "Error al subir imagen"
            ]);
            exit;
        }
    }

    /* INSERT */
    $sql = "INSERT INTO productos
            (nombre, marca, descripcion, stock, id_categoria, imagen, fecha, status)
            VALUES
            ('$nombre', '$marca', '$desc', '$stock', '$cat', '$img', NOW(), '$status')";

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
   CONSULTAR PARA MODIFICAR
========================================================= */
if ($option == "modificarConsultar") {

    $id = intval($_GET['id'] ?? 0);

    $q = mysqli_query($conn, "SELECT * FROM productos WHERE id = $id");

    if (mysqli_num_rows($q) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $data = mysqli_fetch_assoc($q);

    echo json_encode([
        "exito"        => 1,
        "id"           => $data['id'],
        "nombre"       => $data['nombre'],
        "marca"        => $data['marca'],
        "descripcion"  => $data['descripcion'],
        "stock"        => $data['stock'],
        "id_categoria" => $data['id_categoria'],
        "status"       => $data['status'],
        "imagen"       => $data['imagen']
    ]);

    exit;
}


/* =========================================================
   MODIFICAR SERVICIO MÉDICO
========================================================= */
if ($option == "modificar") {

    $id     = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $marca  = trim($_POST['marca'] ?? '');
    $desc   = trim($_POST['descripcion'] ?? '');
    $stock  = intval($_POST['stock'] ?? 0);
    $cat    = intval($_POST['id_categoria'] ?? 0);
    $status = intval($_POST['status'] ?? 1);

    /* VALIDACIONES */
    if ($id <= 0 || $nombre == "" || $marca == "" || $desc == "" || $stock < 0 || $cat <= 0) {
        echo json_encode(["error" => 3]);
        exit;
    }

    /* VERIFICAR EXISTENCIA */
    $verificar = mysqli_query($conn, "SELECT id, imagen FROM productos WHERE id = $id");

    if (mysqli_num_rows($verificar) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $dataActual = mysqli_fetch_assoc($verificar);
    $img = $dataActual['imagen'];

    /* ESCAPAR DATOS */
    $nombre = mysqli_real_escape_string($conn, $nombre);
    $marca  = mysqli_real_escape_string($conn, $marca);
    $desc   = mysqli_real_escape_string($conn, $desc);

    /* NUEVA IMAGEN */
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {

        $dir = $_SERVER['DOCUMENT_ROOT']."/mi_proyecto/img/productos/";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg','jpeg','png','gif','webp'];

        if (!in_array($extension, $permitidas)) {
            echo json_encode([
                "error" => 4,
                "mensaje" => "Formato de imagen no permitido"
            ]);
            exit;
        }

        /* eliminar imagen anterior si existe */
        if ($img != "" && file_exists($dir.$img)) {
            unlink($dir.$img);
        }

        $img = time()."_servicio.".$extension;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $dir.$img)) {
            echo json_encode([
                "error" => 4,
                "mensaje" => "Error al subir imagen"
            ]);
            exit;
        }
    }

    /* UPDATE */
    $sql = "UPDATE productos SET
                nombre='$nombre',
                marca='$marca',
                descripcion='$desc',
                stock='$stock',
                id_categoria='$cat',
                imagen='$img',
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
   ELIMINAR SERVICIO MÉDICO
========================================================= */
if ($option == "eliminar") {

    $id = intval($_GET['id'] ?? 0);

    $verificar = mysqli_query($conn, "SELECT id FROM productos WHERE id = $id");

    if (mysqli_num_rows($verificar) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $sql = "UPDATE productos SET status = 2 WHERE id = $id";

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