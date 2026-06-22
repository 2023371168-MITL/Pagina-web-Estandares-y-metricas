<?php

require_once("tools/mypathdb.php");

/* DATOS */
$email      = trim($_POST['email'] ?? '');
$password   = trim($_POST['password'] ?? '');
$confirmar  = trim($_POST['confirmar'] ?? '');

/* VALIDAR */
if ($email == '' || $password == '' || $confirmar == '') {
    echo json_encode([
        "error"   => 1,
        "mensaje" => "Debe completar todos los campos"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "error"   => 1,
        "mensaje" => "El correo electrónico no es válido"
    ]);
    exit;
}

if ($password !== $confirmar) {
    echo json_encode([
        "error"   => 1,
        "mensaje" => "Las contraseñas no coinciden"
    ]);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode([
        "error"   => 1,
        "mensaje" => "La contraseña debe tener al menos 6 caracteres"
    ]);
    exit;
}

/* ESCAPAR */
$email = mysqli_real_escape_string($conn, $email);

/* BUSCAR USUARIO + ROL */
$sql = "SELECT u.id, r.nombre AS rol
        FROM usuarios u
        INNER JOIN roles r ON u.rolid = r.id
        WHERE u.email = '$email'
          AND u.status = 1
        LIMIT 1";

$q = mysqli_query($conn, $sql);

if (!$q || mysqli_num_rows($q) == 0) {
    echo json_encode([
        "error"   => 1,
        "mensaje" => "No existe un usuario activo con ese correo"
    ]);
    exit;
}

$data = mysqli_fetch_assoc($q);
$rol = strtolower(trim($data['rol']));

/* SOLO PACIENTE Y MEDICO */
if ($rol != 'paciente' && $rol != 'medico' && $rol != 'médico') {
    echo json_encode([
        "error"   => 1,
        "mensaje" => "Solo pacientes y médicos pueden recuperar contraseña desde este apartado"
    ]);
    exit;
}

/* ENCRIPTAR NUEVA CONTRASEÑA */
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

/* ACTUALIZAR */
$sqlUpdate = "UPDATE usuarios
              SET password = '$passwordHash'
              WHERE id = ".$data['id'];

if (mysqli_query($conn, $sqlUpdate)) {
    echo json_encode([
        "exito"   => 1,
        "mensaje" => "Contraseña actualizada correctamente"
    ]);
} else {
    echo json_encode([
        "error"   => 1,
        "mensaje" => "No se pudo actualizar la contraseña: ".mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>