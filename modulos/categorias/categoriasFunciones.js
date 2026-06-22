let modalCategorias = null;

/* =========================================================
   INICIALIZAR MODAL
========================================================= */
document.addEventListener("DOMContentLoaded", function () {
    const modalEl = document.getElementById("modalCategorias");
    if (modalEl) {
        modalCategorias = new bootstrap.Modal(modalEl);
    }
});

/* =========================================================
   PREVIEW DE IMAGEN
========================================================= */
$(document).on("change", "#imagen", function () {
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $("#previewImagen").attr("src", e.target.result);
            $("#contenedorPreview").show();
        };
        reader.readAsDataURL(file);
    } else {
        $("#previewImagen").attr("src", "");
        $("#contenedorPreview").hide();
    }
});

/* =========================================================
   NUEVA ESPECIALIDAD
========================================================= */
function IncluirCategoria() {
    $("#formCategorias")[0].reset();
    $("#idCategoria").val("");
    $("#modalTitleCategoria").html('<i class="bi bi-tags"></i> Nueva Especialidad');
    $("#btnGuardarCategoria").html('<i class="bi bi-save"></i> Guardar');
    $("#previewImagen").attr("src", "");
    $("#contenedorPreview").hide();

    if (modalCategorias) {
        modalCategorias.show();
    }
}

/* =========================================================
   MODIFICAR ESPECIALIDAD
========================================================= */
function ModificarCategoria(id) {
    $.ajax({
        url: "/mi_proyecto/modulos/categorias/categoriasModelo.php?option=consultar&id=" + id,
        type: "GET",
        dataType: "json",

        success: function (r) {
            console.log("CONSULTAR:", r);

            if (r.exito == 1) {
                $("#idCategoria").val(r.id);
                $("#nombre").val(r.nombre);
                $("#descripcion").val(r.descripcion);
                $("#status").val(r.status);

                $("#modalTitleCategoria").html('<i class="bi bi-pencil-square"></i> Modificar Especialidad');
                $("#btnGuardarCategoria").html('<i class="bi bi-save"></i> Actualizar');

                if (r.imagen && r.imagen !== "") {
                    $("#previewImagen").attr("src", "/mi_proyecto/uploads/categorias/" + r.imagen);
                    $("#contenedorPreview").show();
                } else {
                    $("#previewImagen").attr("src", "");
                    $("#contenedorPreview").hide();
                }

                if (modalCategorias) {
                    modalCategorias.show();
                }
            } else {
                swal("Error", r.mensaje || "No se pudo consultar la especialidad", "error");
            }
        },

        error: function (xhr) {
            console.log("ERROR CONSULTAR:", xhr.responseText);
            swal("Error", xhr.responseText || "Error al consultar la especialidad", "error");
        }
    });
}

/* =========================================================
   GUARDAR
========================================================= */
$(document).off("submit", "#formCategorias").on("submit", "#formCategorias", function (e) {
    e.preventDefault();

    let id = $("#idCategoria").val().trim();
    let nombre = $("#nombre").val().trim();
    let descripcion = $("#descripcion").val().trim();

    if (nombre === "" || descripcion === "") {
        swal("Error", "Debe completar nombre y descripción", "error");
        return;
    }

    let option = (id === "") ? "incluir" : "modificar";
    let formData = new FormData(this);

    $.ajax({
        url: "/mi_proyecto/modulos/categorias/categoriasModelo.php?option=" + option,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",

        success: function (r) {
            console.log("GUARDAR:", r);

            if (r.exito == 1) {
                swal("Éxito", r.mensaje, "success").then(() => {
                    location.reload();
                });
            } else {
                swal("Error", r.mensaje || "No se pudo guardar la especialidad", "error");
            }
        },

        error: function (xhr) {
            console.log("ERROR GUARDAR:", xhr.responseText);
            swal("Error", xhr.responseText || "Error del servidor al guardar la especialidad", "error");
        }
    });
});

/* =========================================================
   ELIMINAR
========================================================= */
function EliminarCategoria(id) {
    swal({
        title: "¿Eliminar especialidad?",
        text: "La especialidad se marcará como inactiva",
        icon: "warning",
        buttons: true,
        dangerMode: true
    }).then((ok) => {
        if (ok) {
            $.ajax({
                url: "/mi_proyecto/modulos/categorias/categoriasModelo.php?option=eliminar&id=" + id,
                type: "POST",
                dataType: "json",

                success: function (r) {
                    console.log("ELIMINAR:", r);

                    if (r.exito == 1) {
                        swal("Éxito", r.mensaje, "success").then(() => {
                            location.reload();
                        });
                    } else {
                        swal("Error", r.mensaje || "No se pudo eliminar la especialidad", "error");
                    }
                },

                error: function (xhr) {
                    console.log("ERROR ELIMINAR:", xhr.responseText);
                    swal("Error", xhr.responseText || "Error del servidor al eliminar la especialidad", "error");
                }
            });
        }
    });
}