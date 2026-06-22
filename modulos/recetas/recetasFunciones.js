function abrirModalReceta() {
    $("#modalTitleReceta").text("Nueva receta médica");
    $("#btnActionReceta").text("Guardar");
    $("#formReceta")[0].reset();
    $("#idReceta").val("");
}

$(document).ready(function () {

    $("#formReceta").off("submit").on("submit", function (e) {
        e.preventDefault();

        let id = $("#idReceta").val();
        let opcion = id == "" ? "incluir" : "modificar";

        let formData = new FormData(this);

        $.ajax({
            url: "/mi_proyecto/modulos/recetas/recetasModelo.php?option=" + opcion,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            success: function (r) {
                console.log("RESPUESTA RECETA:", r);

                if (r.exito == 1) {
                    let mensaje = (opcion == "incluir")
                        ? "Receta médica registrada correctamente"
                        : "Receta médica actualizada correctamente";

                    swal("Éxito", mensaje, "success")
                        .then(() => {
                            location.reload();
                        });

                } else if (r.error == 2) {
                    swal("Error", "La receta no existe", "error");
                } else if (r.error == 3) {
                    swal("Error", "Debe completar todos los campos", "error");
                } else {
                    swal("Error", r.mensaje || "No se pudo guardar la receta", "error");
                }
            },

            error: function (xhr) {
                console.log("ERROR SERVIDOR RECETA:", xhr.responseText);
                swal("Error", "Error en servidor", "error");
            }
        });
    });

});

function ModificarReceta(id) {

    $("#modalTitleReceta").text("Modificar receta médica");
    $("#btnActionReceta").text("Actualizar");

    $.ajax({
        url: "/mi_proyecto/modulos/recetas/recetasModelo.php?option=consultar&id=" + id,
        type: "GET",
        dataType: "json",

        success: function (r) {
            console.log("DATOS RECETA:", r);

            if (r.exito == 1) {
                $("#idReceta").val(r.id);
                $("#id_paciente").val(r.id_paciente);
                $("#cedula_profesional").val(r.cedula_profesional);
                $("#diagnostico").val(r.diagnostico);
                $("#medicamentos").val(r.medicamentos);
                $("#indicaciones").val(r.indicaciones);

                $("#modalReceta").modal("show");
            } else {
                swal("Error", "Receta no encontrada", "error");
            }
        },

        error: function (xhr) {
            console.log("ERROR CONSULTA RECETA:", xhr.responseText);
            swal("Error", "No se pudo consultar la receta", "error");
        }
    });
}

function EliminarReceta(id) {
    swal({
        title: "¿Eliminar receta médica?",
        text: "La receta se dará de baja lógica",
        icon: "warning",
        buttons: true,
        dangerMode: true
    }).then((ok) => {

        if (ok) {
            $.ajax({
                url: "/mi_proyecto/modulos/recetas/recetasModelo.php?option=eliminar&id=" + id,
                type: "POST",
                dataType: "json",

                success: function (r) {
                    if (r.exito == 1) {
                        swal("Éxito", "Receta eliminada correctamente", "success")
                            .then(() => {
                                location.reload();
                            });
                    } else {
                        swal("Error", r.mensaje || "No se pudo eliminar la receta", "error");
                    }
                },

                error: function (xhr) {
                    console.log("ERROR ELIMINAR RECETA:", xhr.responseText);
                    swal("Error", "Error en servidor", "error");
                }
            });
        }

    });
}