<?php
session_start();

if(isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Care - Inicio de sesión</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SWEETALERT -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <style>
        body{
            min-height:100vh;
            background: linear-gradient(135deg, #e9f5ff 0%, #f8fffb 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-wrapper{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:30px 15px;
        }

        .login-card{
            width:100%;
            max-width:1100px;
            border:none;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 15px 40px rgba(0,0,0,.12);
            background:#fff;
        }

        .left-panel{
            background: linear-gradient(180deg, #2f5fb3 0%, #78b24b 100%);
            color:#fff;
            padding:45px 35px;
            height:100%;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            text-align:center;
        }

        .left-panel img{
            max-width:220px;
            margin-bottom:20px;
            background:#fff;
            padding:12px;
            border-radius:18px;
            box-shadow:0 8px 20px rgba(0,0,0,.15);
        }

        .left-panel h2{
            font-weight:700;
            margin-bottom:10px;
        }

        .left-panel p{
            font-size:15px;
            line-height:1.7;
            opacity:.95;
        }

        .right-panel{
            padding:35px 30px;
            background:#fff;
        }

        .brand-title{
            color:#2f5fb3;
            font-weight:700;
        }

        .nav-pills .nav-link{
            border-radius:12px;
            font-weight:600;
            color:#2f5fb3;
            border:1px solid #dbe6f5;
            margin-right:8px;
            margin-bottom:8px;
        }

        .nav-pills .nav-link.active{
            background:#2f5fb3;
            color:#fff;
            border-color:#2f5fb3;
        }

        .form-control{
            border-radius:12px;
            min-height:48px;
            border:1px solid #d9e2ef;
            box-shadow:none !important;
        }

        .form-control:focus{
            border-color:#2f5fb3;
        }

        .btn-nexus{
            background:#2f5fb3;
            color:#fff;
            border:none;
            border-radius:12px;
            min-height:48px;
            font-weight:600;
        }

        .btn-nexus:hover{
            background:#264f96;
            color:#fff;
        }

        .btn-green{
            background:#78b24b;
            color:#fff;
            border:none;
            border-radius:12px;
            min-height:48px;
            font-weight:600;
        }

        .btn-green:hover{
            background:#679a3f;
            color:#fff;
        }

        .tab-pane h4{
            color:#2f5fb3;
            font-weight:700;
        }

        .small-note{
            font-size:13px;
            color:#6c757d;
        }

        .input-group-text{
            border-radius:12px 0 0 12px;
            border:1px solid #d9e2ef;
            background:#f8fbff;
        }

        .form-label{
            font-weight:600;
            color:#334;
        }

        .divider{
            width:70px;
            height:4px;
            background:linear-gradient(90deg,#78b24b,#2f5fb3);
            border-radius:10px;
            margin:10px auto 0;
        }

        @media(max-width:991px){
            .left-panel{
                padding:30px 20px;
            }

            .right-panel{
                padding:25px 20px;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid login-wrapper">
    <div class="card login-card">
        <div class="row g-0">

            <!-- PANEL IZQUIERDO -->
            <div class="col-lg-5">
                <div class="left-panel">
                    <img src="/mi_proyecto/img/nexus-care-logo.png" alt="Nexus Care Logo">

                    <h2>Nexus Care</h2>
                    <div class="divider mb-3"></div>

                    <p>
                        Plataforma web para la gestión de <strong>citas médicas</strong>,
                        <strong>servicios médicos</strong>, <strong>especialidades</strong>,
                        usuarios y reportes del sistema.
                    </p>

                    <p class="mt-2 mb-0">
                        Inicia sesión para acceder a tu panel de trabajo,
                        consultar tus citas y gestionar tu información en el sistema.
                    </p>
                </div>
            </div>

            <!-- PANEL DERECHO -->
            <div class="col-lg-7">
                <div class="right-panel">

                    <div class="mb-4">
                        <h2 class="brand-title mb-1">Bienvenido a Nexus Care</h2>
                        <p class="text-muted mb-0">
                            Accede a tu cuenta, regístrate como paciente o recupera tu contraseña.
                        </p>
                    </div>

                    <!-- TABS -->
                    <ul class="nav nav-pills mb-4" id="tabsLogin" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-login" data-bs-toggle="pill"
                                    data-bs-target="#panel-login" type="button" role="tab">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-registro" data-bs-toggle="pill"
                                    data-bs-target="#panel-registro" type="button" role="tab">
                                <i class="bi bi-person-plus"></i> Registrarme
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-recuperar" data-bs-toggle="pill"
                                    data-bs-target="#panel-recuperar" type="button" role="tab">
                                <i class="bi bi-key"></i> Recuperar contraseña
                            </button>
                        </li>

                    </ul>

                    <div class="tab-content">

                        <!-- ===================================== -->
                        <!-- LOGIN -->
                        <!-- ===================================== -->
                        <div class="tab-pane fade show active" id="panel-login" role="tabpanel">
                            <h4 class="mb-3">Inicio de sesión</h4>

                            <form id="formLogin">

                                <div class="mb-3">
                                    <label class="form-label">Correo electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-nexus w-100">
                                    <i class="bi bi-box-arrow-in-right"></i> Ingresar
                                </button>
                            </form>
                        </div>

                        <!-- ===================================== -->
                        <!-- REGISTRO PACIENTE -->
                        <!-- ===================================== -->
                        <div class="tab-pane fade" id="panel-registro" role="tabpanel">
                            <h4 class="mb-3">Registro de paciente</h4>

                            <form id="formRegistroPaciente">

                                <div class="mb-3">
                                    <label class="form-label">Nombre completo</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="reg_nombre" name="nombre" placeholder="Nombre completo" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Correo electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="reg_email" name="email" placeholder="correo@ejemplo.com" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Número de teléfono</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control" id="reg_telefono" name="telefono" placeholder="4421234567" maxlength="10" required>
                                    </div>
                                    <small class="text-muted">Ingresa 10 dígitos.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="reg_password" name="password" placeholder="Crea una contraseña" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirmar contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                        <input type="password" class="form-control" id="reg_confirmar" name="confirmar" placeholder="Confirma tu contraseña" required>
                                    </div>
                                </div>

                                <p class="small-note">
                                    Solo los <strong>pacientes</strong> pueden registrarse desde esta pantalla.
                                    Los médicos deben ser registrados por el administrador del sistema.
                                </p>

                                <button type="submit" class="btn btn-green w-100">
                                    <i class="bi bi-person-plus"></i> Registrarme como paciente
                                </button>
                            </form>
                        </div>

                        <!-- ===================================== -->
                        <!-- RECUPERAR PASSWORD -->
                        <!-- ===================================== -->
                        <div class="tab-pane fade" id="panel-recuperar" role="tabpanel">
                            <h4 class="mb-3">Recuperar contraseña</h4>

                            <form id="formRecuperarPassword">

                                <div class="mb-3">
                                    <label class="form-label">Correo electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="rec_email" name="email" placeholder="correo@ejemplo.com" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nueva contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="rec_password" name="password" placeholder="Nueva contraseña" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirmar nueva contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                        <input type="password" class="form-control" id="rec_confirmar" name="confirmar" placeholder="Confirma tu nueva contraseña" required>
                                    </div>
                                </div>

                                <p class="small-note">
                                    La recuperación de contraseña está disponible para
                                    <strong>pacientes</strong> y <strong>médicos</strong>.
                                </p>

                                <button type="submit" class="btn btn-nexus w-100">
                                    <i class="bi bi-key"></i> Actualizar contraseña
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* =========================================================
   LOGIN
========================================================= */
$("#formLogin").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "loginModelo.php",
        type: "POST",
        data: {
            email: $("#email").val(),
            password: $("#password").val()
        },
        dataType: "json",
        success: function(r){
            if(r.exito == 1){
                swal("Bienvenido", "Inicio de sesión correcto", "success")
                .then(() => {
                    window.location.href = "index.php";
                });
            }else{
                swal("Error", r.mensaje || "Correo o contraseña incorrectos", "error");
            }
        },
        error: function(xhr){
            console.log(xhr.responseText);
            swal("Error", "Ocurrió un error en el servidor", "error");
        }
    });
});

/* =========================================================
   REGISTRO DE PACIENTE
========================================================= */
$("#formRegistroPaciente").submit(function(e){
    e.preventDefault();

    let nombre     = $("#reg_nombre").val().trim();
    let email      = $("#reg_email").val().trim();
    let telefono   = $("#reg_telefono").val().trim();
    let password   = $("#reg_password").val().trim();
    let confirmar  = $("#reg_confirmar").val().trim();

    if(nombre === "" || email === "" || telefono === "" || password === "" || confirmar === ""){
        swal("Error", "Debe completar todos los campos", "error");
        return;
    }

    if(!/^[0-9]{10}$/.test(telefono)){
        swal("Error", "El número de teléfono debe contener exactamente 10 dígitos", "error");
        return;
    }

    if(password !== confirmar){
        swal("Error", "Las contraseñas no coinciden", "error");
        return;
    }

    $.ajax({
        url: "registroPaciente.php",
        type: "POST",
        data: {
            nombre: nombre,
            email: email,
            telefono: telefono,
            password: password,
            confirmar: confirmar
        },
        dataType: "json",
        success: function(r){
            if(r.exito == 1){
                swal("Éxito", "Paciente registrado correctamente. Ahora puedes iniciar sesión.", "success")
                .then(() => {
                    $("#formRegistroPaciente")[0].reset();
                    $("#tab-login").click();
                });
            }else{
                swal("Error", r.mensaje || "No se pudo registrar el paciente", "error");
            }
        },
        error: function(xhr){
            console.log(xhr.responseText);
            swal("Error", "Ocurrió un error en el servidor", "error");
        }
    });
});

/* =========================================================
   RECUPERAR PASSWORD
========================================================= */
$("#formRecuperarPassword").submit(function(e){
    e.preventDefault();

    let email      = $("#rec_email").val().trim();
    let password   = $("#rec_password").val().trim();
    let confirmar  = $("#rec_confirmar").val().trim();

    if(email === "" || password === "" || confirmar === ""){
        swal("Error", "Debe completar todos los campos", "error");
        return;
    }

    if(password !== confirmar){
        swal("Error", "Las contraseñas no coinciden", "error");
        return;
    }

    $.ajax({
        url: "recuperarPassword.php",
        type: "POST",
        data: {
            email: email,
            password: password,
            confirmar: confirmar
        },
        dataType: "json",
        success: function(r){
            if(r.exito == 1){
                swal("Éxito", "La contraseña fue actualizada correctamente", "success")
                .then(() => {
                    $("#formRecuperarPassword")[0].reset();
                    $("#tab-login").click();
                });
            }else{
                swal("Error", r.mensaje || "No se pudo actualizar la contraseña", "error");
            }
        },
        error: function(xhr){
            console.log(xhr.responseText);
            swal("Error", "Ocurrió un error en el servidor", "error");
        }
    });
});
</script>

</body>
</html>