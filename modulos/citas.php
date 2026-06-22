<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$page_title = "Citas";

$rolUsuario = strtolower(trim($_SESSION['rol'] ?? ''));

if($rolUsuario === 'paciente'){
    $page_title = "Mis Citas";
}elseif($rolUsuario === 'administrador'){
    $page_title = "Historial de Citas";
}else{
    $page_title = "Citas";
}

include_once("tools/header.php");
include_once("tools/navbar.php");
include_once("tools/sidebar.php");
?>

<main class="app-main">

    <!-- HEADER -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-6">
                    <h3 class="mb-0"><?php echo $page_title; ?></h3>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="/mi_proyecto/?page=home">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php echo $page_title; ?>
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- CONTENIDO -->
    <div class="app-content">
        <div class="container-fluid">

            <div class="card">

                <!-- HEADER CARD -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <?php
                        if($rolUsuario === 'paciente'){
                            echo "Gestión de Mis Citas";
                        }elseif($rolUsuario === 'administrador'){
                            echo "Gestión / Historial de Citas";
                        }else{
                            echo "Citas Médicas";
                        }
                        ?>
                    </h5>

                    <div>
                        <?php
                        /*
                            El botón de "Nueva Cita" ya está controlado dentro de citasModal.php
                            y solo se mostrará si el usuario es paciente.
                        */
                        include_once("modulos/citas/citasModal.php");
                        ?>
                    </div>
                </div>

                <!-- TABLA -->
                <?php include_once("modulos/citas/citasTabla.php"); ?>

            </div>

        </div>
    </div>

</main>

<?php include_once("tools/footer.php"); ?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- FUNCIONES CITAS -->
<script src="/mi_proyecto/modulos/citas/citasFunciones.js"></script>