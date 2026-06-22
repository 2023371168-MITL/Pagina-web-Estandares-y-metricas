<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$rolUsuario = strtolower(trim($_SESSION['rol'] ?? ''));
$idSesion   = intval($_SESSION['id'] ?? 0);

/*
    FILTRO SEGÚN ROL:
    - paciente -> solo sus citas
    - médico   -> solo citas asignadas a él
    - admin    -> todas
*/
$where = " WHERE c.status IN (1,2) ";

if ($rolUsuario == 'paciente') {
    $where .= " AND c.id_paciente = $idSesion ";
}
elseif ($rolUsuario == 'medico' || $rolUsuario == 'médico') {
    $where .= " AND c.id_medico = $idSesion ";
}

$sql = "SELECT 
            c.*,
            p.nombre AS paciente,
            m.nombre AS medico,
            cat.nombre AS especialidad,
            s.nombre AS servicio
        FROM citas c
        LEFT JOIN usuarios p ON c.id_paciente = p.id
        LEFT JOIN usuarios m ON c.id_medico = m.id
        LEFT JOIN categorias cat ON c.id_especialidad = cat.id
        LEFT JOIN productos s ON c.id_servicio = s.id
        $where
        ORDER BY c.id DESC";

$resultado = mysqli_query($conn, $sql);
?>

<div class="card-body">

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width:10px">#</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Especialidad</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                    <th style="width:170px">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $contador = 1;

                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {

                        $estadoLower = strtolower(trim($row['estado']));
                ?>
                    <tr>
                        <td><?php echo $contador++; ?></td>

                        <td><?php echo htmlspecialchars($row['paciente'] ?? 'Sin paciente'); ?></td>
                        <td><?php echo htmlspecialchars($row['medico'] ?? 'Sin médico'); ?></td>
                        <td><?php echo htmlspecialchars($row['especialidad'] ?? 'Sin especialidad'); ?></td>
                        <td><?php echo htmlspecialchars($row['servicio'] ?? 'Sin servicio'); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_cita']); ?></td>
                        <td><?php echo htmlspecialchars($row['hora_cita']); ?></td>

                        <td>
                            <?php if ($estadoLower == 'pendiente') { ?>
                                <span class="badge text-bg-warning">Pendiente</span>
                            <?php } elseif ($estadoLower == 'confirmada') { ?>
                                <span class="badge text-bg-primary">Confirmada</span>
                            <?php } elseif ($estadoLower == 'atendida') { ?>
                                <span class="badge text-bg-success">Atendida</span>
                            <?php } elseif ($estadoLower == 'cancelada') { ?>
                                <span class="badge text-bg-danger">Cancelada</span>
                            <?php } else { ?>
                                <span class="badge text-bg-secondary"><?php echo htmlspecialchars($row['estado']); ?></span>
                            <?php } ?>
                        </td>

                        <td>
                            <?php echo !empty($row['observaciones']) ? htmlspecialchars($row['observaciones']) : 'Sin observaciones'; ?>
                        </td>

                        <td class="text-center">

                            <!-- =========================
                                 PACIENTE
                            ========================== -->
                            <?php if ($rolUsuario == 'paciente') { ?>

                                <?php if ($estadoLower != 'atendida' && $estadoLower != 'cancelada') { ?>

                                    <button
                                        class="btn btn-primary btn-sm me-1"
                                        onclick="ModificarCita('<?php echo $row['id']; ?>')"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalCita"
                                        title="Modificar cita">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="EliminarCita('<?php echo $row['id']; ?>')"
                                        title="Cancelar cita">
                                        <i class="bi bi-x-circle"></i>
                                    </button>

                                <?php } else { ?>
                                    <span class="text-muted">Sin acciones</span>
                                <?php } ?>


                            <!-- =========================
                                 MÉDICO
                            ========================== -->
                            <?php } elseif ($rolUsuario == 'medico' || $rolUsuario == 'médico') { ?>

                                <?php if ($estadoLower == 'pendiente' || $estadoLower == 'confirmada') { ?>
                                    <button
                                        class="btn btn-success btn-sm"
                                        onclick="MarcarAtendida('<?php echo $row['id']; ?>')"
                                        title="Marcar como atendida">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                <?php } elseif ($estadoLower == 'atendida') { ?>
                                    <span class="badge text-bg-success">Atendida</span>
                                <?php } else { ?>
                                    <span class="text-muted">Sin acciones</span>
                                <?php } ?>


                            <!-- =========================
                                 ADMINISTRADOR
                            ========================== -->
                            <?php } elseif ($rolUsuario == 'administrador') { ?>

                                <button
                                    class="btn btn-primary btn-sm me-1"
                                    onclick="ModificarCita('<?php echo $row['id']; ?>')"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCita"
                                    title="Modificar cita">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <?php if ($estadoLower != 'atendida' && $estadoLower != 'cancelada') { ?>
                                    <button
                                        class="btn btn-success btn-sm me-1"
                                        onclick="MarcarAtendida('<?php echo $row['id']; ?>')"
                                        title="Marcar como atendida">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                <?php } ?>

                                <?php if ($estadoLower != 'cancelada') { ?>
                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="EliminarCita('<?php echo $row['id']; ?>')"
                                        title="Cancelar cita">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                <?php } ?>

                            <?php } else { ?>
                                <span class="text-muted">Sin acciones</span>
                            <?php } ?>

                        </td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted">
                            No hay citas registradas
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php mysqli_close($conn); ?>