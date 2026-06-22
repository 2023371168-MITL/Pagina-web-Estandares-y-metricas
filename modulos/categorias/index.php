<?php
$page_title = "Especialidades Médicas";

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
                    <p class="text-muted mb-0">Administración de especialidades médicas</p>
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

                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Especialidades registradas</h5>

                            <button class="btn btn-primary"
                                    type="button"
                                    onclick="IncluirCategoria()">
                                <i class="bi bi-plus-circle"></i> Nueva Especialidad
                            </button>
                        </div>

                        <div class="card-body">
                            <?php include_once(__DIR__ . "/categoriasTabla.php"); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</main>

<?php include_once(__DIR__ . "/categoriasModal.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/mi_proyecto/tools/footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="/mi_proyecto/modulos/categorias/categoriasFunciones.js?v=2"></script>