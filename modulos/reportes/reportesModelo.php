<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$option = $_GET['option'] ?? '';

/* =========================================================
   INCLUIR REPORTE
========================================================= */
if ($option == "incluir") {

    $id_usuario  = $_SESSION['id'];
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($titulo == '' || $descripcion == '') {
        echo json_encode(["error" => 3]);
        exit;
    }

    $titulo      = mysqli_real_escape_string($conn, $titulo);
    $descripcion = mysqli_real_escape_string($conn, $descripcion);

    $sql = "INSERT INTO reportes
            (id_usuario, titulo, descripcion, estado, fecha_reporte, status)
            VALUES
            ('$id_usuario', '$titulo', '$descripcion', 'Pendiente', NOW(), 1)";

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
   CONSULTAR REPORTE
========================================================= */
if ($option == "consultar") {

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $q = mysqli_query($conn, "SELECT * FROM reportes WHERE id=$id AND status=1");

    if (mysqli_num_rows($q) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $data = mysqli_fetch_assoc($q);

    // Si no es admin, solo puede consultar su propio reporte
    if ($_SESSION['rolid'] != 1 && $data['id_usuario'] != $_SESSION['id']) {
        echo json_encode(["error" => 2]);
        exit;
    }

    echo json_encode([
        "exito"       => 1,
        "id"          => $data['id'],
        "titulo"      => $data['titulo'],
        "descripcion" => $data['descripcion'],
        "estado"      => $data['estado']
    ]);

    exit;
}


/* =========================================================
   MODIFICAR REPORTE
========================================================= */
if ($option == "modificar") {

    $id          = intval($_POST['id'] ?? 0);
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estado      = trim($_POST['estado'] ?? 'Pendiente');

    if ($id <= 0 || $titulo == '' || $descripcion == '') {
        echo json_encode(["error" => 3]);
        exit;
    }

    $q = mysqli_query($conn, "SELECT * FROM reportes WHERE id=$id AND status=1");

    if (mysqli_num_rows($q) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $data = mysqli_fetch_assoc($q);

    // Paciente / médico solo pueden modificar sus reportes y si siguen pendientes
    if ($_SESSION['rolid'] != 1) {
        if ($data['id_usuario'] != $_SESSION['id'] || $data['estado'] != 'Pendiente') {
            echo json_encode(["error" => 2]);
            exit;
        }

        $estado = $data['estado']; // no lo puede cambiar
    }

    $titulo      = mysqli_real_escape_string($conn, $titulo);
    $descripcion = mysqli_real_escape_string($conn, $descripcion);
    $estado      = mysqli_real_escape_string($conn, $estado);

    // si admin lo marca completado aquí también guarda fecha
    $fechaSolucion = "";
    if($estado == "Completado" && empty($data['fecha_solucion'])){
        $fechaSolucion = ", fecha_solucion = NOW()";
    }

    $sql = "UPDATE reportes SET
                titulo='$titulo',
                descripcion='$descripcion',
                estado='$estado'
                $fechaSolucion
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
   ADMIN: COMPLETAR REPORTE
========================================================= */
if ($option == "completar") {

    if($_SESSION['rolid'] != 1){
        echo json_encode(["error" => 2]);
        exit;
    }

    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $verificar = mysqli_query($conn, "SELECT id FROM reportes WHERE id=$id AND status=1");

    if (mysqli_num_rows($verificar) == 0) {
        echo json_encode(["error" => 2]);
        exit;
    }

    $sql = "UPDATE reportes
            SET estado='Completado',
                fecha_solucion=NOW()
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
   PDF DE REPORTE
========================================================= */
if ($option == "pdf") {

    if($_SESSION['rolid'] != 1){
        exit("Acceso denegado");
    }

    $id = intval($_GET['id'] ?? 0);

    $sql = "SELECT r.*, u.nombre AS usuario, ro.nombre AS rol
            FROM reportes r
            INNER JOIN usuarios u ON r.id_usuario = u.id
            INNER JOIN roles ro ON u.rolid = ro.id
            WHERE r.id = $id
            LIMIT 1";

    $q = mysqli_query($conn, $sql);

    if(mysqli_num_rows($q) == 0){
        exit("Reporte no encontrado");
    }

    $data = mysqli_fetch_assoc($q);

    header("Content-Type: text/html; charset=utf-8");
    ?>
    <html>
    <head>
        <title>Reporte #<?php echo $data['id']; ?></title>
        <style>
            body{ font-family: Arial, sans-serif; padding:20px; }
            h2{ margin-bottom:20px; }
            .campo{ margin-bottom:10px; }
            .titulo{ font-weight:bold; }
            .box{
                border:1px solid #ccc;
                padding:15px;
                border-radius:8px;
            }
        </style>
    </head>
    <body onload="window.print()">

        <h2>Reporte del sistema - Nexus Care</h2>

        <div class="box">
            <div class="campo"><span class="titulo">ID:</span> <?php echo $data['id']; ?></div>
            <div class="campo"><span class="titulo">Usuario:</span> <?php echo htmlspecialchars($data['usuario']); ?></div>
            <div class="campo"><span class="titulo">Rol:</span> <?php echo htmlspecialchars($data['rol']); ?></div>
            <div class="campo"><span class="titulo">Título:</span> <?php echo htmlspecialchars($data['titulo']); ?></div>
            <div class="campo"><span class="titulo">Descripción:</span> <?php echo nl2br(htmlspecialchars($data['descripcion'])); ?></div>
            <div class="campo"><span class="titulo">Estado:</span> <?php echo htmlspecialchars($data['estado']); ?></div>
            <div class="campo"><span class="titulo">Fecha reporte:</span> <?php echo htmlspecialchars($data['fecha_reporte']); ?></div>
            <div class="campo"><span class="titulo">Fecha solución:</span> <?php echo !empty($data['fecha_solucion']) ? htmlspecialchars($data['fecha_solucion']) : 'Sin resolver'; ?></div>
        </div>

    </body>
    </html>
    <?php
    exit;
}

mysqli_close($conn);
?>
