<?php
$page_title = "Recetas Médicas";

include_once("tools/header.php");
include_once("tools/navbar.php");
include_once("tools/sidebar.php");
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
                        <li class="breadcrumb-item"><a href="/mi_proyecto">Inicio</a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
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
                        <div class="card-header">
                            <?php include_once("modulos/recetas/recetasModal.php"); ?>
                        </div>

                        <div class="card-body">
                            <?php include_once("modulos/recetas/recetasTabla.php"); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</main>

<?php include_once("tools/footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="/mi_proyecto/modulos/recetas/recetasFunciones.js"></script>