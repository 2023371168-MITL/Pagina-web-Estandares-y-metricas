<?php

$page_title = "Reportes";

include_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/header.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/navbar.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/sidebar.php');

?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Nexus Care - <?php echo $page_title; ?></h3>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="/mi_proyecto">Inicio</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php echo $page_title; ?>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Gestión de reportes del sistema</h5>

                            <?php if($_SESSION['rolid'] != 1){ ?>
                                <button class="btn btn-primary"
                                        onclick="abrirModalReporte()"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalReporte">
                                    <i class="bi bi-plus-circle"></i> Nuevo Reporte
                                </button>
                            <?php } ?>
                        </div>

                        <div class="card-body">
                            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/modulos/reportes/reportesTabla.php'); ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/footer.php'); ?>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/modulos/reportes/reportesModal.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="/mi_proyecto/modulos/reportes/reportesFunciones.js"></script>