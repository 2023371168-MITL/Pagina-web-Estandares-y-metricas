<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

/*
|--------------------------------------------------------------------------
| DATOS DE SESIÓN
|--------------------------------------------------------------------------
*/
$rolUsuario = strtolower(trim($_SESSION['rol'] ?? ''));
$idSesion   = intval($_SESSION['id'] ?? 0);

/*
|--------------------------------------------------------------------------
| PACIENTES (SOLO ADMIN)
|--------------------------------------------------------------------------
*/
$pacientes = null;

if ($rolUsuario == 'administrador') {
    $sqlPacientes = "
        SELECT u.id, u.nombre
        FROM usuarios u
        INNER JOIN roles r ON u.rolid = r.id
        WHERE u.status = 1
          AND LOWER(r.nombre) = 'paciente'
        ORDER BY u.nombre ASC
    ";
    $pacientes = mysqli_query($conn, $sqlPacientes);
}

/*
|--------------------------------------------------------------------------
| ESPECIALIDADES
|--------------------------------------------------------------------------
*/
$sqlEspecialidades = "
    SELECT id, nombre
    FROM categorias
    WHERE status = 1
    ORDER BY nombre ASC
";
$especialidades = mysqli_query($conn, $sqlEspecialidades);

/*
|--------------------------------------------------------------------------
| SERVICIOS MÉDICOS
|--------------------------------------------------------------------------
*/
$sqlServicios = "
    SELECT id, nombre
    FROM productos
    WHERE status = 1
    ORDER BY nombre ASC
";
$servicios = mysqli_query($conn, $sqlServicios);
?>

<?php if ($rolUsuario == 'paciente' || $rolUsuario == 'administrador') { ?>
    <!-- BOTÓN NUEVA CITA -->
    <button class="btn btn-primary"
        onclick="abrirModalCita()"
        data-bs-toggle="modal"
        data-bs-target="#modalCita">
        <i class="bi bi-plus-circle"></i> Nueva Cita
    </button>
<?php } ?>

<!-- MODAL -->
<div class="modal fade" id="modalCita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="modalTitleCita">
                    <i class="bi bi-calendar2-check"></i> Nueva Cita Médica
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <form id="formCita">
                    <input type="hidden" id="idCita" name="id">

                    <?php if ($rolUsuario == 'paciente') { ?>
                        <!-- El paciente no elige paciente; se usa su sesión -->
                        <input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $idSesion; ?>">
                    <?php } ?>

                    <!-- PACIENTE (SOLO ADMIN) -->
                    <?php if ($rolUsuario == 'administrador') { ?>
                        <div class="mb-3">
                            <label class="form-label">Paciente</label>
                            <select class="form-control" name="id_paciente" id="id_paciente" required>
                                <option value="">Seleccione un paciente</option>

                                <?php
                                if ($pacientes && mysqli_num_rows($pacientes) > 0) {
                                    while ($p = mysqli_fetch_assoc($pacientes)) {
                                ?>
                                        <option value="<?php echo $p['id']; ?>">
                                            <?php echo htmlspecialchars($p['nombre']); ?>
                                        </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    <?php } ?>

                    <!-- ESPECIALIDAD -->
                    <div class="mb-3">
                        <label class="form-label">Especialidad médica</label>
                        <select class="form-control" name="id_especialidad" id="id_especialidad" required>
                            <option value="">Seleccione una especialidad</option>

                            <?php
                            if ($especialidades && mysqli_num_rows($especialidades) > 0) {
                                while ($e = mysqli_fetch_assoc($especialidades)) {
                            ?>
                                    <option value="<?php echo $e['id']; ?>">
                                        <?php echo htmlspecialchars($e['nombre']); ?>
                                    </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- MÉDICO -->
                    <div class="mb-3">
                        <label class="form-label">Doctor / Médico</label>
                        <select class="form-control" name="id_medico" id="id_medico" required>
                            <option value="">Primero seleccione una especialidad</option>
                        </select>
                    </div>

                    <!-- SERVICIO -->
                    <div class="mb-3">
                        <label class="form-label">Servicio médico</label>
                        <select class="form-control" name="id_servicio" id="id_servicio" required>
                            <option value="">Seleccione un servicio</option>

                            <?php
                            if ($servicios && mysqli_num_rows($servicios) > 0) {
                                while ($s = mysqli_fetch_assoc($servicios)) {
                            ?>
                                    <option value="<?php echo $s['id']; ?>">
                                        <?php echo htmlspecialchars($s['nombre']); ?>
                                    </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- FECHA -->
                    <div class="mb-3">
                        <label class="form-label">Fecha de cita</label>
                        <input
                            type="date"
                            class="form-control"
                            name="fecha_cita"
                            id="fecha_cita"
                            min="<?php echo date('Y-m-d'); ?>"
                            required>
                    </div>

                    <!-- HORA -->
                    <div class="mb-3">
                        <label class="form-label">Hora de cita</label>
                        <input type="time" class="form-control" name="hora_cita" id="hora_cita" required>
                    </div>

                    <!-- ESTADO -->
                    <?php if ($rolUsuario == 'administrador') { ?>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-control" name="estado" id="estado" required>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Confirmada">Confirmada</option>
                                <option value="Atendida">Atendida</option>
                                <option value="Cancelada">Cancelada</option>
                            </select>
                        </div>
                    <?php } else { ?>
                        <!-- Paciente no decide el estado -->
                        <input type="hidden" name="estado" id="estado" value="Pendiente">
                    <?php } ?>

                    <!-- OBSERVACIONES -->
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea
                            class="form-control"
                            name="observaciones"
                            id="observaciones"
                            rows="4"
                            placeholder="Describe brevemente el motivo de la cita o alguna observación"></textarea>
                    </div>

                    <!-- BOTONES -->
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary" id="btnActionCita">
                            Guardar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>