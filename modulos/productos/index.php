<?php

$page_title = "Servicios Médicos";

include_once($_SERVER['DOCUMENT_ROOT']."/mi_proyecto/tools/header.php");
include_once($_SERVER['DOCUMENT_ROOT']."/mi_proyecto/tools/navbar.php");
include_once($_SERVER['DOCUMENT_ROOT']."/mi_proyecto/tools/sidebar.php");

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

            <div class="card">

                <div class="card-header">
                    <?php include_once(__DIR__ . "/productoModal.php"); ?>
                </div>

                <div class="card-body">
                    <?php include_once(__DIR__ . "/productoTabla.php"); ?>
                </div>

            </div>

        </div>
    </div>

</main>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/mi_proyecto/tools/footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="/mi_proyecto/modulos/productos/productoFunciones.js"></script>