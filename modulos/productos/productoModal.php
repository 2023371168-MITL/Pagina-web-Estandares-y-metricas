<!-- BOTON -->
<button class="btn btn-success"
    onclick="abrirModalProducto()"
    data-bs-toggle="modal"
    data-bs-target="#modalProducto">

    <i class="bi bi-plus-circle"></i> Agregar Servicio Médico
</button>

<!-- MODAL -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="modalTitleProducto">
                    Nuevo Servicio Médico
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formProducto" enctype="multipart/form-data">

                    <input type="hidden" id="idProducto" name="id">

                    <!-- NOMBRE -->
                    <div class="mb-3">
                        <label class="form-label">Nombre del servicio</label>
                        <input type="text"
                            class="form-control"
                            name="nombre"
                            id="nombreProducto"
                            required>
                    </div>

                    <!-- TIPO / ÁREA -->
                    <div class="mb-3">
                        <label class="form-label">Tipo / Área médica</label>
                        <input type="text"
                            class="form-control"
                            name="marca"
                            id="marcaProducto"
                            required>
                    </div>

                    <!-- ESPECIALIDAD -->
                    <div class="mb-3">
                        <label class="form-label">Especialidad</label>

                        <select class="form-control"
                            name="id_categoria"
                            id="id_categoriaProducto"
                            required>

                            <option value="">Seleccione</option>

                            <?php
                            require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

                            $q = mysqli_query($conn, "SELECT id, nombre FROM categorias WHERE status = 1 ORDER BY nombre ASC");

                            while($cat = mysqli_fetch_assoc($q)){
                                echo "<option value='{$cat['id']}'>".htmlspecialchars($cat['nombre'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- DESCRIPCION -->
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control"
                            name="descripcion"
                            id="descripcionProducto"
                            rows="4"
                            required></textarea>
                    </div>

                    <!-- DISPONIBILIDAD / CUPO -->
                    <div class="mb-3">
                        <label class="form-label">Disponibilidad / Cupo</label>
                        <input type="number"
                            class="form-control"
                            name="stock"
                            id="stockProducto"
                            min="0"
                            required>
                    </div>

                    <!-- ESTADO -->
                    <div class="mb-3">
                        <label class="form-label">Estado</label>

                        <select class="form-control"
                            name="status"
                            id="statusProducto">

                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>

                    <!-- IMAGEN -->
                    <div class="mb-3">
                        <label class="form-label">Imagen</label>
                        <input type="file"
                            class="form-control"
                            name="imagen"
                            id="imagenProducto"
                            accept="image/*">
                    </div>

                    <!-- BOTONES -->
                    <div class="text-end">
                        <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit"
                            class="btn btn-success"
                            id="btnActionProducto">
                            Guardar
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</div>