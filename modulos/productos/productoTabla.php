<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$sql = "SELECT p.*, c.nombre AS categoria
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id
        ORDER BY p.id DESC";

$resultado = mysqli_query($conn, $sql);

?>

<div class="card-body">

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">

            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Servicio</th>
                    <th>Área / Tipo</th>
                    <th>Especialidad</th>
                    <th>Descripción</th>
                    <th>Disponibilidad</th>
                    <th>Imagen</th>
                    <th>Estatus</th>
                    <th style="width:120px">Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php
                $i = 1;

                while($row = mysqli_fetch_assoc($resultado)){
                ?>
                    <tr>

                        <td><?php echo $i++; ?></td>

                        <td>
                            <strong><?php echo htmlspecialchars($row['nombre']); ?></strong>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['marca']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['categoria'] ?? 'Sin especialidad'); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['descripcion']); ?>
                        </td>

                        <td>
                            <?php echo (int)$row['stock']; ?>
                        </td>

                        <td class="text-center">
                            <?php if($row['imagen'] != ""){ ?>
                                <img src="/mi_proyecto/img/productos/<?php echo htmlspecialchars($row['imagen']); ?>"
                                    style="width:60px;height:60px;object-fit:cover;border-radius:5px;">
                            <?php }else{ ?>
                                <span class="badge text-bg-secondary">Sin imagen</span>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if($row['status'] == 1){ ?>
                                <span class="badge text-bg-success">Activo</span>
                            <?php }else{ ?>
                                <span class="badge text-bg-danger">Inactivo</span>
                            <?php } ?>
                        </td>

                        <td class="text-center">

                            <!-- EDITAR -->
                            <button class="btn btn-primary btn-sm me-1"
                                onclick="ModificarProducto('<?php echo $row['id']; ?>')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalProducto">

                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- ELIMINAR -->
                            <button class="btn btn-danger btn-sm"
                                onclick="EliminarProducto('<?php echo $row['id']; ?>')">

                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>
                <?php } ?>

            </tbody>

        </table>
    </div>

</div>

<?php mysqli_close($conn); ?>