<?php
$rolSesion = strtolower(trim($_SESSION['rol'] ?? ''));
$esMedico = ($rolSesion == 'medico' || $rolSesion == 'médico');
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h5 class="mb-0">Listado de recetas médicas</h5>

    <?php if($esMedico){ ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReceta" onclick="abrirModalReceta()">
            <i class="bi bi-file-earmark-medical"></i> Nueva receta
        </button>
    <?php } ?>
</div>

<div class="modal fade" id="modalReceta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="formReceta">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitleReceta">Nueva receta médica</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="idReceta">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Paciente</label>
                            <select name="id_paciente" id="id_paciente" class="form-control" required>
                                <option value="">Seleccione un paciente</option>
                                <?php
                                require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

                                $sqlPac = "SELECT u.id, u.nombre
                                           FROM usuarios u
                                           INNER JOIN roles r ON u.rolid = r.id
                                           WHERE u.status = 1
                                             AND LOWER(r.nombre) = 'paciente'
                                           ORDER BY u.nombre ASC";
                                $resPac = mysqli_query($conn, $sqlPac);

                                if($resPac){
                                    while($p = mysqli_fetch_assoc($resPac)){
                                        echo '<option value="'.$p['id'].'">'.htmlspecialchars($p['nombre']).'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cédula profesional</label>
                            <input type="text" name="cedula_profesional" id="cedula_profesional" class="form-control" maxlength="50" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Diagnóstico</label>
                            <textarea name="diagnostico" id="diagnostico" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Medicamentos / tratamiento</label>
                            <textarea name="medicamentos" id="medicamentos" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Indicaciones</label>
                            <textarea name="indicaciones" id="indicaciones" class="form-control" rows="4" required></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnActionReceta">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>