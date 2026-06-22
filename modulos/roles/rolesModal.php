<div class="modal fade" id="modalRoles" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="modalTitleRol">
                    <i class="bi bi-shield-lock"></i> Nuevo Rol
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <form id="formRoles">

                    <input type="hidden" id="idRol" name="id">

                    <!-- NOMBRE -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>

                    <!-- DESCRIPCION -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input type="text" id="descripcion" name="descripcion" class="form-control" required>
                    </div>

                    <!-- ACCESOS -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Accesos</label>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" id="txtAccesoDashboard" class="form-check-input">
                                    <label class="form-check-label" for="txtAccesoDashboard">Dashboard</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" id="txtAccesoUsuarios" class="form-check-input">
                                    <label class="form-check-label" for="txtAccesoUsuarios">Usuarios</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" id="txtAccesoRoles" class="form-check-input">
                                    <label class="form-check-label" for="txtAccesoRoles">Roles</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" id="txtAccesoCategorias" class="form-check-input">
                                    <label class="form-check-label" for="txtAccesoCategorias">Especialidades</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" id="txtAccesoProductos" class="form-check-input">
                                    <label class="form-check-label" for="txtAccesoProductos">Servicios Médicos</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" id="txtAccesoCarrito" class="form-check-input">
                                    <label class="form-check-label" for="txtAccesoCarrito">Servicios</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" id="txtAccesoCitas" class="form-check-input">
                                    <label class="form-check-label" for="txtAccesoCitas">Citas</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" id="txtAccesoReportes" class="form-check-input">
                                    <label class="form-check-label" for="txtAccesoReportes">Reportes</label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- STATUS -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>

                    <!-- BOTONES -->
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary" id="btnGuardarRol">
                            Guardar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>