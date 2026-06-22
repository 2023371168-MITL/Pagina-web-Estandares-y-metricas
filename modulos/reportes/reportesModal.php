<div class="modal fade" id="modalReporte" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="modalTitleReporte">
                    Nuevo Reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formReporte">

                    <input type="hidden" id="idReporte" name="id">

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del problema</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del problema</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required></textarea>
                    </div>

                    <?php if($_SESSION['rolid'] == 1){ ?>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="Pendiente">Pendiente</option>
                            <option value="En revisión">En revisión</option>
                            <option value="Completado">Completado</option>
                        </select>
                    </div>
                    <?php } ?>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary" id="btnGuardarReporte">
                            Guardar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>