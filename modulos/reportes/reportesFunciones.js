// ========================================
// ABRIR MODAL NUEVO REPORTE
// ========================================
function abrirModalReporte() {
    $("#modalTitleReporte").text("Nuevo Reporte");
    $("#btnGuardarReporte").text("Guardar");
    $("#formReporte")[0].reset();
    $("#idReporte").val("");
}


// ========================================
// GUARDAR / MODIFICAR REPORTE
// ========================================
$(document).ready(function () {

    $("#formReporte").on("submit", function (e) {
        e.preventDefault();

        let id = $("#idReporte").val();
        let opcion = id == "" ? "incluir" : "modificar";

        let formData = new FormData(this);

        $.ajax({
            url: "/mi_proyecto/modulos/reportes/reportesModelo.php?option=" + opcion,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            success: function (r) {
                console.log("RESPUESTA REPORTE:", r);

                if (r.exito == 1) {
                    let mensaje = (opcion == "incluir")
                        ? "Reporte registrado correctamente"
                        : "Reporte actualizado correctamente";

                    swal("Éxito", mensaje, "success")
                        .then(() => {
                            location.reload();
                        });

                } else if (r.error == 2) {
                    swal("Error", "El reporte no existe", "error");
                } else if (r.error == 3) {
                    swal("Error", "Debe completar todos los campos", "error");
                } else {
                    swal("Error", r.mensaje || "No se pudo guardar el reporte", "error");
                }
            },

            error: function (xhr) {
                console.log(xhr.responseText);
                swal("Error", "Error en servidor", "error");
            }
        });

    });

});


// ========================================
// CONSULTAR REPORTE PARA MODIFICAR
// ========================================
function ModificarReporte(id) {

    $("#modalTitleReporte").text("Modificar Reporte");
    $("#btnGuardarReporte").text("Actualizar");

    $.ajax({
        url: "/mi_proyecto/modulos/reportes/reportesModelo.php?option=consultar&id=" + id,
        type: "GET",
        dataType: "json",

        success: function (r) {
            console.log("DATOS REPORTE:", r);

            if (r.exito == 1) {
                $("#idReporte").val(r.id);
                $("#titulo").val(r.titulo);
                $("#descripcion").val(r.descripcion);

                if ($("#estado").length) {
                    $("#estado").val(r.estado);
                }

                $("#modalReporte").modal("show");

            } else {
                swal("Error", "Reporte no encontrado", "error");
            }
        },

        error: function (xhr) {
            console.log(xhr.responseText);
            swal("Error", "No se pudo consultar el reporte", "error");
        }
    });
}


// ========================================
// ADMIN: MARCAR COMO COMPLETADO
// ========================================
function CompletarReporte(id) {

    swal({
        title: "¿Marcar reporte como completado?",
        text: "Se registrará la fecha de solución",
        icon: "warning",
        buttons: true
    })
    .then((ok) => {
        if (ok) {

            $.ajax({
                url: "/mi_proyecto/modulos/reportes/reportesModelo.php?option=completar&id=" + id,
                type: "POST",
                dataType: "json",

                success: function (r) {
                    if (r.exito == 1) {
                        swal("Éxito", "Reporte completado correctamente", "success")
                            .then(() => { location.reload(); });
                    } else {
                        swal("Error", "No se pudo completar el reporte", "error");
                    }
                },

                error: function (xhr) {
                    console.log(xhr.responseText);
                    swal("Error", "Error en servidor", "error");
                }
            });

        }
    });

}