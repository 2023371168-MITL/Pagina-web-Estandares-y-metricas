<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$sql = "SELECT * FROM categorias ORDER BY id DESC";
$query = mysqli_query($conn, $sql);
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th style="width:70px;">#</th>
                <th>Especialidad</th>
                <th>Descripción</th>
                <th style="width:140px;">Imagen</th>
                <th style="width:150px;">Fecha</th>
                <th style="width:110px;">Estatus</th>
                <th style="width:140px;">Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if($query && mysqli_num_rows($query) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>

                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>

                    <td><?php echo htmlspecialchars($row['descripcion']); ?></td>

                    <td class="text-center">
                        <?php if(!empty($row['imagen'])): ?>
                            <img src="/mi_proyecto/uploads/categorias/<?php echo htmlspecialchars($row['imagen']); ?>"
                                 alt="Especialidad"
                                 style="width:90px; height:70px; object-fit:cover; border-radius:8px; border:1px solid #ddd;">
                        <?php else: ?>
                            <span class="text-muted">Sin imagen</span>
                        <?php endif; ?>
                    </td>

                    <td><?php echo htmlspecialchars($row['fecha']); ?></td>

                    <td>
                        <?php if($row['status'] == 1): ?>
                            <span class="badge bg-success">Activa</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactiva</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <button class="btn btn-primary btn-sm me-1"
                                type="button"
                                onclick="ModificarCategoria(<?php echo (int)$row['id']; ?>)"
                                title="Modificar">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <button class="btn btn-danger btn-sm"
                                type="button"
                                onclick="EliminarCategoria(<?php echo (int)$row['id']; ?>)"
                                title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">No hay especialidades registradas</td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>