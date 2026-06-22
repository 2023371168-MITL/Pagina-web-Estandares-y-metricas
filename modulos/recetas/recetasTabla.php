<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$rolSesion = strtolower(trim($_SESSION['rol'] ?? ''));
$idSesion  = intval($_SESSION['id'] ?? 0);

$where = " WHERE re.status = 1 ";

if($rolSesion == 'paciente'){
    $where .= " AND re.id_paciente = $idSesion ";
}

if($rolSesion == 'medico' || $rolSesion == 'médico'){
    $where .= " AND re.id_medico = $idSesion ";
}

$sql = "SELECT re.*,
               p.nombre AS paciente,
               m.nombre AS medico
        FROM recetas re
        INNER JOIN usuarios p ON p.id = re.id_paciente
        INNER JOIN usuarios m ON m.id = re.id_medico
        $where
        ORDER BY re.id DESC";

$res = mysqli_query($conn, $sql);
?>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Diagnóstico</th>
                <th>Fecha</th>
                <th>Cédula</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if($res && mysqli_num_rows($res) > 0){ ?>
            <?php while($row = mysqli_fetch_assoc($res)){ ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['paciente']); ?></td>
                    <td><?php echo htmlspecialchars($row['medico']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['diagnostico'])); ?></td>
                    <td><?php echo $row['fecha_receta']; ?></td>
                    <td><?php echo htmlspecialchars($row['cedula_profesional']); ?></td>
                    <td>
                        <a href="/mi_proyecto/modulos/recetas/recetaPDF.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-danger btn-sm">
                            PDF
                        </a>

                        <?php if($rolSesion == 'medico' || $rolSesion == 'médico'){ ?>
                            <button class="btn btn-warning btn-sm" onclick="ModificarReceta(<?php echo $row['id']; ?>)">
                                Editar
                            </button>

                            <button class="btn btn-secondary btn-sm" onclick="EliminarReceta(<?php echo $row['id']; ?>)">
                                Eliminar
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <td colspan="7" class="text-center">No hay recetas registradas</td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>