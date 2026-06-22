<?php

$page_title = "Roles de usuario";

include_once("tools/header.php");
include_once("tools/navbar.php");
include_once("tools/sidebar.php");

?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-6">
                    <h3 class="mb-0">Ventas en línea - <?php echo $page_title; ?></h3>
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

                        <!-- HEADER -->
                        <div class="card-header">
                            <button class="btn btn-primary"
                                onclick="Incluir()"
                                data-bs-toggle="modal"
                                data-bs-target="#modalRoles">
                                <i class="bi bi-plus-circle"></i> Agregar rol
                            </button>
                        </div>

                        <!-- TABLA -->
                        <div class="card-body">
                            <?php include_once("modulos/roles/rolesTabla.php"); ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</main>

<!-- MODAL FUERA DEL CARD -->
<?php include_once("modulos/roles/rolesModal.php"); ?>

<?php include_once("tools/footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="/mi_proyecto/modulos/roles/rolesFunciones.js"></script>