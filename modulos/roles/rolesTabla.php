<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$sql = "SELECT * FROM roles ORDER BY id DESC";
$resultado = mysqli_query($conn, $sql);
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th style="width:10px">#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Accesos</th>
                <th>Fecha</th>
                <th style="width:120px">Status</th>
                <th style="width:120px">Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $contador = 1;

            while($row = mysqli_fetch_assoc($resultado)){

                $accesos = [];
                if(!empty($row['accesos'])){
                    $accesos = preg_split('/[;,]/', $row['accesos']);
                    $accesos = array_map('trim', $accesos);
                }
            ?>
                <tr>
                    <td><?php echo $contador++; ?></td>

                    <td>
                        <strong><?php echo htmlspecialchars($row['nombre']); ?></strong>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['descripcion']); ?>
                    </td>

                    <td>
                        <?php if(empty($accesos)){ ?>
                            <span class="text-muted">Sin accesos</span>
                        <?php }else{ ?>

                            <?php foreach($accesos as $acceso){ 
                                $accesoMin = strtolower(trim($acceso));
                                $label = $acceso;

                                if($accesoMin == 'dashboard') $label = 'Dashboard';
                                if($accesoMin == 'usuarios') $label = 'Usuarios';
                                if($accesoMin == 'roles') $label = 'Roles';
                                if($accesoMin == 'categorias') $label = 'Especialidades';
                                if($accesoMin == 'productos') $label = 'Servicios Médicos';
                                if($accesoMin == 'carrito') $label = 'Servicios';
                                if($accesoMin == 'citas') $label = 'Citas';
                                if($accesoMin == 'reportes') $label = 'Reportes';
                            ?>
                                <span class="badge text-bg-primary me-1 mb-1">
                                    <?php echo htmlspecialchars($label); ?>
                                </span>
                            <?php } ?>

                        <?php } ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['fecha']); ?>
                    </td>

                    <td>
                        <?php if($row['status'] == 1){ ?>
                            <span class="badge text-bg-success">Activo</span>
                        <?php }else{ ?>
                            <span class="badge text-bg-danger">Inactivo</span>
                        <?php } ?>
                    </td>

                    <td class="text-center">
                        <button
                            class="btn btn-primary btn-sm me-1"
                            onclick="Modificar('<?php echo $row['id']; ?>')"
                            data-bs-toggle="modal"
                            data-bs-target="#modalRoles"
                            title="Modificar">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <button
                            class="btn btn-danger btn-sm"
                            onclick="Eliminar('<?php echo $row['id']; ?>')"
                            title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php mysqli_close($conn); ?>