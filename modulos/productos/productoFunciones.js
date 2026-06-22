// ======================================
// ABRIR MODAL NUEVO SERVICIO MÉDICO
// ======================================
function abrirModalProducto() {

    $("#modalTitleProducto").text("Nuevo Servicio Médico");
    $("#btnActionProducto").text("Guardar");

    $("#formProducto")[0].reset();
    $("#idProducto").val("");
}


// ======================================
// GUARDAR SERVICIO MÉDICO (INCLUIR / MODIFICAR)
// ======================================
$(document).ready(function () {

    $("#formProducto").on("submit", function (e) {
        e.preventDefault();

        let id = $("#idProducto").val();
        let opcion = (id === "") ? "incluir" : "modificar";

        let formData = new FormData(this);

        $.ajax({
            url: "/mi_proyecto/modulos/productos/productoModelo.php?option=" + opcion,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            success: function (r) {
                console.log("RESPUESTA PRODUCTO:", r);

                if (r.exito == 1) {

                    let mensaje = (opcion === "incluir")
                        ? "Servicio médico registrado correctamente"
                        : "Servicio médico modificado correctamente";

                    swal("Éxito", mensaje, "success")
                        .then(() => {
                            window.location.href = "/mi_proyecto/?page=productos";
                        });

                } else if (r.error == 2) {

                    swal("Error", "El servicio médico no existe", "error");

                } else if (r.error == 3) {

                    swal("Error", "Debe completar correctamente los datos", "error");

                } else if (r.error == 4) {

                    swal("Error", r.mensaje || "No se pudo guardar el servicio médico", "error");

                } else {

                    swal("Error", "Respuesta desconocida del servidor", "error");

                }
            },

            error: function (xhr) {
                console.log("ERROR SERVIDOR PRODUCTO:", xhr.responseText);
                swal("Error", "Error en servidor", "error");
            }
        });
    });

});


// ======================================
// MODIFICAR SERVICIO MÉDICO
// ======================================
function ModificarProducto(id) {

    $("#modalTitleProducto").text("Modificar Servicio Médico");
    $("#btnActionProducto").text("Actualizar");

    $.ajax({
        url: "/mi_proyecto/modulos/productos/productoModelo.php?option=modificarConsultar&id=" + id,
        type: "GET",
        dataType: "json",

        success: function (r) {
            console.log("DATOS PRODUCTO:", r);

            if (r.exito == 1) {

                $("#idProducto").val(r.id);
                $("#nombreProducto").val(r.nombre);
                $("#marcaProducto").val(r.marca);
                $("#descripcionProducto").val(r.descripcion);
                $("#stockProducto").val(r.stock);
                $("#id_categoriaProducto").val(r.id_categoria);
                $("#statusProducto").val(r.status);

                $("#modalProducto").modal("show");

            } else {
                swal("Error", "Servicio médico no encontrado", "error");
            }
        },

        error: function (xhr) {
            console.log("ERROR CONSULTA PRODUCTO:", xhr.responseText);
            swal("Error", "No se pudo consultar el servicio médico", "error");
        }
    });
}


// ======================================
// ELIMINAR SERVICIO MÉDICO
// ======================================
function EliminarProducto(id) {

    swal({
        title: "¿Eliminar servicio médico?",
        text: "El servicio se marcará como inactivo",
        icon: "warning",
        buttons: true,
        dangerMode: true
    })
    .then((ok) => {

        if (ok) {

            $.ajax({
                url: "/mi_proyecto/modulos/productos/productoModelo.php?option=eliminar&id=" + id,
                type: "POST",
                dataType: "json",

                success: function (r) {
                    console.log("ELIMINAR PRODUCTO:", r);

                    if (r.exito == 1) {
                        swal("Éxito", "Servicio médico eliminado correctamente", "success")
                            .then(() => {
                                window.location.href = "/mi_proyecto/?page=productos";
                            });
                    } else {
                        swal("Error", "No se pudo eliminar el servicio médico", "error");
                    }
                },

                error: function (xhr) {
                    console.log("ERROR ELIMINAR PRODUCTO:", xhr.responseText);
                    swal("Error", "Error en servidor", "error");
                }
            });

        }

    });
}