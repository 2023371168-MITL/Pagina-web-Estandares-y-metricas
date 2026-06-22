<?php
session_start();
require_once("tools/mypathdb.php");

header('Content-Type: application/json; charset=utf-8');

/* =========================================================
   RECIBIR DATOS
========================================================= */
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

/* =========================================================
   VALIDAR CAMPOS
========================================================= */
if ($email === '' || $password === '') {
    echo json_encode([
        "error" => 1,
        "mensaje" => "Debe completar correo y contraseña"
    ]);
    exit;
}

/* =========================================================
   ESCAPAR
========================================================= */
$email = mysqli_real_escape_string($conn, $email);

/* =========================================================
   BUSCAR USUARIO ACTIVO
========================================================= */
$sql = "SELECT id, nombre, email, password, rolid, status
        FROM usuarios
        WHERE email = '$email'
        AND status = 1
        LIMIT 1";

$res = mysqli_query($conn, $sql);

if (!$res) {
    echo json_encode([
        "error" => 1,
        "mensaje" => "Error al consultar usuario: " . mysqli_error($conn)
    ]);
    exit;
}

if (mysqli_num_rows($res) == 0) {
    echo json_encode([
        "error" => 1,
        "mensaje" => "El correo no existe o el usuario está inactivo"
    ]);
    exit;
}

$usuario = mysqli_fetch_assoc($res);

/* =========================================================
   VERIFICAR PASSWORD
========================================================= */
if (!password_verify($password, $usuario['password'])) {
    echo json_encode([
        "error" => 1,
        "mensaje" => "La contraseña es incorrecta"
    ]);
    exit;
}

/* =========================================================
   OBTENER DATOS DEL ROL
========================================================= */
$rolid = intval($usuario['rolid']);

$sqlRol = "SELECT id, nombre, accesos
           FROM roles
           WHERE id = $rolid
           AND status = 1
           LIMIT 1";

$resRol = mysqli_query($conn, $sqlRol);

if (!$resRol) {
    echo json_encode([
        "error" => 1,
        "mensaje" => "Error al consultar el rol: " . mysqli_error($conn)
    ]);
    exit;
}

if (mysqli_num_rows($resRol) == 0) {
    echo json_encode([
        "error" => 1,
        "mensaje" => "El rol del usuario no existe o está inactivo"
    ]);
    exit;
}

$rol = mysqli_fetch_assoc($resRol);

/* =========================================================
   CREAR SESIÓN
========================================================= */
$_SESSION['id']       = $usuario['id'];
$_SESSION['usuario']  = $usuario['nombre'];
$_SESSION['email']    = $usuario['email'];
$_SESSION['rolid']    = $rol['id'];
$_SESSION['rol']      = $rol['nombre'];
$_SESSION['accesos']  = $rol['accesos'];

/* =========================================================
   RESPUESTA EXITOSA
========================================================= */
echo json_encode([
    "exito" => 1,
    "mensaje" => "Inicio de sesión correcto",
    "usuario" => $usuario['nombre'],
    "rol" => $rol['nombre'],
    "accesos" => $rol['accesos']
]);

mysqli_close($conn);
?>
