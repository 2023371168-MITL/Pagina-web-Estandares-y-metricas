// ======================================
// CARGAR MÉDICOS SEGÚN ESPECIALIDAD
// ======================================
function cargarMedicosPorEspecialidad(idEspecialidad, medicoSeleccionado = "") {

    $("#id_medico").html('<option value="">Cargando médicos...</option>');

    if (idEspecialidad == "" || idEspecialidad == 0) {
        $("#id_medico").html('<option value="">Primero seleccione una especialidad</option>');
        return;
    }

    $.ajax({
        url: "/mi_proyecto/modulos/citas/citasModelo.php?option=obtenerMedicos&id_especialidad=" + idEspecialidad,
        type: "GET",
        dataType: "json",

        success: function (respuesta) {

            let opciones = '<option value="">Seleccione un médico</option>';

            if (respuesta.length > 0) {
                respuesta.forEach(function (medico) {
                    let selected = (medicoSeleccionado == medico.id) ? "selected" : "";
                    opciones += `<option value="${medico.id}" ${selected}>${medico.nombre}</option>`;
                });
            } else {
                opciones = '<option value="">No hay médicos en esta especialidad</option>';
            }

            $("#id_medico").html(opciones);
        },

        error: function (xhr) {
            console.log("ERROR AL CARGAR MÉDICOS:", xhr.responseText);
            $("#id_medico").html('<option value="">Error al cargar médicos</option>');
        }
    });
}


// ======================================
// ABRIR MODAL NUEVA CITA
// ======================================
function abrirModalCita() {
    $("#modalTitleCita").text("Nueva Cita Médica");
    $("#formCita")[0].reset();
    $("#idCita").val("");
    $("#btnActionCita").text("Guardar");
    $("#id_medico").html('<option value="">Primero seleccione una especialidad</option>');
}


// ======================================
// CAMBIO DE ESPECIALIDAD
// ======================================
$(document).on("change", "#id_especialidad", function () {
    let idEspecialidad = $(this).val();
    cargarMedicosPorEspecialidad(idEspecialidad);
});


// ======================================
// GUARDAR / MODIFICAR CITA
// ======================================
$(document).ready(function () {

    $("#formCita").off("submit").on("submit", function (e) {
        e.preventDefault();

        let id = $("#idCita").val();
        let opcion = id == "" ? "incluir" : "modificar";

        let formData = new FormData(this);

        $.ajax({
            url: "/mi_proyecto/modulos/citas/citasModelo.php?option=" + opcion,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            success: function (r) {
                console.log("RESPUESTA CITA:", r);

                if (r.exito == 1) {

                    let mensaje = (opcion == "incluir")
                        ? "Cita médica registrada correctamente"
                        : "Cita médica modificada correctamente";

                    swal("Éxito", mensaje, "success")
                        .then(() => {
                            location.reload();
                        });

                } else if (r.error == 2) {

                    swal("Error", "La cita no existe", "error");

                } else if (r.error == 3) {

                    swal("Error", "Debe completar todos los datos de la cita", "error");

                } else if (r.error == 4) {

                    swal("Error", r.mensaje || "Error al guardar la cita", "error");

                } else {

                    swal("Error", "Respuesta desconocida del servidor", "error");

                }
            },

            error: function (xhr) {
                console.log("ERROR SERVIDOR:", xhr.responseText);
                swal("Error", "Error en servidor", "error");
            }
        });

    });

});


// ======================================
// MODIFICAR CITA
// ======================================
function ModificarCita(id) {

    $("#modalTitleCita").text("Modificar Cita Médica");
    $("#btnActionCita").text("Actualizar");

    $.ajax({
        url: "/mi_proyecto/modulos/citas/citasModelo.php?option=modificarConsultar&id=" + id,
        type: "GET",
        dataType: "json",

        success: function (r) {

            console.log("DATOS CITA:", r);

            if (r.exito == 1) {

                $("#idCita").val(r.id);
                $("#id_paciente").val(r.id_paciente);
                $("#id_especialidad").val(r.id_especialidad);
                $("#id_servicio").val(r.id_servicio);
                $("#fecha_cita").val(r.fecha_cita);
                $("#hora_cita").val(r.hora_cita);
                $("#estado").val(r.estado);
                $("#observaciones").val(r.observaciones);

                cargarMedicosPorEspecialidad(r.id_especialidad, r.id_medico);

                $("#modalCita").modal("show");

            } else {
                swal("Error", "Cita no encontrada", "error");
            }
        },

        error: function (xhr) {
            console.log("ERROR CONSULTA:", xhr.responseText);
            swal("Error", "No se pudo consultar la cita", "error");
        }
    });
}


// ======================================
// ELIMINAR / CANCELAR CITA
// ======================================
function EliminarCita(id) {

    swal({
        title: "¿Cancelar cita médica?",
        text: "La cita se marcará como cancelada",
        icon: "warning",
        buttons: true,
        dangerMode: true
    })
    .then((ok) => {

        if (ok) {

            $.ajax({
                url: "/mi_proyecto/modulos/citas/citasModelo.php?option=eliminar&id=" + id,
                type: "POST",
                dataType: "json",

                success: function (r) {

                    if (r.exito == 1) {
                        swal("Éxito", "Cita cancelada correctamente", "success")
                            .then(() => { location.reload(); });
                    } else {
                        swal("Error", r.mensaje || "No se pudo cancelar la cita", "error");
                    }

                },

                error: function (xhr) {
                    console.log("ERROR ELIMINAR:", xhr.responseText);
                    swal("Error", "Error en servidor", "error");
                }
            });

        }

    });

}


// ======================================
// MARCAR CITA COMO ATENDIDA
// ======================================
function MarcarAtendida(id) {

    swal({
        title: "¿Marcar cita como atendida?",
        text: "La cita cambiará a estado Atendida",
        icon: "info",
        buttons: true
    })
    .then((ok) => {

        if (ok) {

            $.ajax({
                url: "/mi_proyecto/modulos/citas/citasModelo.php?option=marcarAtendida&id=" + id,
                type: "POST",
                dataType: "json",

                success: function (r) {
                    console.log("RESPUESTA ATENDER:", r);

                    if (r.exito == 1) {
                        swal("Éxito", "La cita fue marcada como atendida", "success")
                            .then(() => {
                                location.reload();
                            });
                    } else {
                        swal("Error", r.mensaje || "No se pudo actualizar la cita", "error");
                    }
                },

                error: function (xhr) {
                    console.log("ERROR ATENDER:", xhr.responseText);
                    swal("Error", "Error en servidor", "error");
                }
            });

        }

    });
}