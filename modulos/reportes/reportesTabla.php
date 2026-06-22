<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$idUsuario = $_SESSION['id'];
$rolid     = $_SESSION['rolid'];

/*
    rolid = 1 => Administrador
    Paciente / Médico => solo ven sus reportes
*/

if($rolid == 1){
    $sql = "SELECT r.*, u.nombre AS usuario, ro.nombre AS rol
            FROM reportes r
            INNER JOIN usuarios u ON r.id_usuario = u.id
            INNER JOIN roles ro ON u.rolid = ro.id
            WHERE r.status = 1
            ORDER BY r.id DESC";
}else{
    $sql = "SELECT r.*, u.nombre AS usuario, ro.nombre AS rol
            FROM reportes r
            INNER JOIN usuarios u ON r.id_usuario = u.id
            INNER JOIN roles ro ON u.rolid = ro.id
            WHERE r.status = 1
              AND r.id_usuario = '$idUsuario'
            ORDER BY r.id DESC";
}

$resultado = mysqli_query($conn, $sql);
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha reporte</th>
                <th>Estado</th>
                <th>Fecha solución</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $contador = 1;
            while($row = mysqli_fetch_assoc($resultado)){
            ?>
            <tr>
                <td><?php echo $contador++; ?></td>
                <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                <td><?php echo htmlspecialchars($row['rol']); ?></td>
                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_reporte']); ?></td>

                <td>
                    <?php if($row['estado'] == "Pendiente"){ ?>
                        <span class="badge bg-warning text-dark">Pendiente</span>
                    <?php }elseif($row['estado'] == "En revisión"){ ?>
                        <span class="badge bg-info text-dark">En revisión</span>
                    <?php }else{ ?>
                        <span class="badge bg-success">Completado</span>
                    <?php } ?>
                </td>

                <td>
                    <?php echo !empty($row['fecha_solucion']) ? htmlspecialchars($row['fecha_solucion']) : 'Sin resolver'; ?>
                </td>

                <td>
                    <?php if($rolid == 1){ ?>
                        
                        <!-- ADMIN: puede editar estado -->
                        <button class="btn btn-primary btn-sm me-1"
                                onclick="ModificarReporte('<?php echo $row['id']; ?>')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalReporte">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <?php if($row['estado'] != "Completado"){ ?>
                        <button class="btn btn-success btn-sm me-1"
                                onclick="CompletarReporte('<?php echo $row['id']; ?>')">
                            <i class="bi bi-check-circle"></i>
                        </button>
                        <?php } ?>

                        <a href="/mi_proyecto/modulos/reportes/reportesModelo.php?option=pdf&id=<?php echo $row['id']; ?>"
                           class="btn btn-danger btn-sm"
                           target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>

                    <?php }else{ ?>

                        <!-- PACIENTE / MÉDICO: solo pueden editar si sigue pendiente -->
                        <?php if($row['estado'] == "Pendiente"){ ?>
                        <button class="btn btn-primary btn-sm"
                                onclick="ModificarReporte('<?php echo $row['id']; ?>')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalReporte">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <?php }else{ ?>
                            <span class="text-muted">Sin acciones</span>
                        <?php } ?>

                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>