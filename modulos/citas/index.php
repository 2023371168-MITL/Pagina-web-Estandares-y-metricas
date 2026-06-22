<?php

$page_title = "Citas Médicas";

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
                    <p class="text-muted mb-0">
                        Administración, agenda y seguimiento de citas médicas del sistema.
                    </p>
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

            <div class="card shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <h5 class="card-title mb-1">Citas registradas</h5>
                        <small class="text-muted">
                            Aquí puedes registrar, editar y administrar las citas médicas de Nexus Care.
                        </small>
                    </div>

                    <div>
                        <?php include_once("modulos/citas/citasModal.php"); ?>
                    </div>

                </div>

                <div class="card-body">
                    <?php include_once("modulos/citas/citasTabla.php"); ?>
                </div>

            </div>

        </div>
    </div>

</main>

<?php include_once("tools/footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="/mi_proyecto/modulos/citas/citasFunciones.js"></script>