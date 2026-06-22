// ================================
// ABRIR MODAL NUEVO USUARIO
// ================================
function abrirModalUsuario() {
    $("#modalTitle").text("Nuevo Usuario");

    $("#formUsuarios")[0].reset();
    $("#idUsuario").val("");

    // Password obligatoria cuando es nuevo usuario
    $("#password").prop("required", true);
    $("#grupoPassword").show();

    // Ocultar especialidad por defecto
    $("#grupoEspecialidad").hide();
    $("#id_especialidad").val("");
}


// ================================
// MOSTRAR / OCULTAR ESPECIALIDAD SEGÚN ROL
// ================================
function mostrarCamposRol() {
    let textoRol = $("#rolid option:selected").text().toLowerCase().trim();

    if (textoRol === "medico" || textoRol === "médico") {
        $("#grupoEspecialidad").show();
    } else {
        $("#grupoEspecialidad").hide();
        $("#id_especialidad").val("");
    }
}


// ================================
// GUARDAR (INCLUIR Y MODIFICAR)
// ================================
$(document).ready(function () {

    // Detectar cambio de rol para mostrar especialidad
    $("#rolid").on("change", function () {
        mostrarCamposRol();
    });

    $("#formUsuarios").on("submit", function (e) {
        e.preventDefault();

        let id = $("#idUsuario").val();
        let opcion = id == "" ? "incluir" : "modificar";

        let formData = new FormData(this);

        $.ajax({
            url: "/mi_proyecto/modulos/usuarios/usuariosModelo.php?option=" + opcion,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            success: function (r) {
                console.log("RESPUESTA USUARIO:", r);

                if (r.exito == 1) {

                    let mensaje = (opcion == "incluir")
                        ? "Usuario registrado correctamente"
                        : "Usuario modificado correctamente";

                    swal("Éxito", mensaje, "success")
                        .then(() => {
                            location.reload();
                        });

                } else if (r.error == 1) {

                    swal("Error", "El correo del usuario ya existe", "error");

                } else if (r.error == 2) {

                    swal("Error", "Usuario no existe", "error");

                } else if (r.error == 5) {

                    swal("Error", "Debe seleccionar una especialidad para el médico", "error");

                } else {

                    swal("Error", "Datos incorrectos o incompletos", "error");
                }
            },

            error: function (xhr) {
                console.log("ERROR SERVIDOR:", xhr.responseText);
                swal("Error", "Error en servidor", "error");
            }
        });

    });

});


// ================================
// EDITAR USUARIO
// ================================
function Modificar(id) {

    $("#modalTitle").text("Modificar Usuario");

    // En edición no obligamos a volver a poner password
    $("#grupoPassword").hide();
    $("#password").prop("required", false);

    $.ajax({
        url: "/mi_proyecto/modulos/usuarios/usuariosModelo.php?option=modificarConsultar&id=" + id,
        type: "GET",
        dataType: "json",

        success: function (r) {

            console.log("DATOS USUARIO:", r);

            if (r.exito == 1) {

                $("#idUsuario").val(r.id);
                $("#nombre").val(r.nombre);
                $("#direccion").val(r.direccion);
                $("#telefono").val(r.telefono);
                $("#email").val(r.email);
                $("#rolid").val(r.rolid);
                $("#status").val(r.status);

                // Mostrar u ocultar especialidad según el rol
                mostrarCamposRol();

                // Cargar id_especialidad si el usuario es médico
                if (r.id_especialidad !== null && r.id_especialidad !== "") {
                    $("#id_especialidad").val(r.id_especialidad);
                } else {
                    $("#id_especialidad").val("");
                }

                $("#modalUsuarios").modal("show");

            } else {
                swal("Error", "Usuario no encontrado", "error");
            }

        },

        error: function (xhr) {
            console.log("ERROR CONSULTA:", xhr.responseText);
            swal("Error", "No se pudo consultar el usuario", "error");
        }
    });

}


// ================================
// ELIMINAR USUARIO
// ================================
function Eliminar(id) {

    swal({
        title: "¿Eliminar usuario?",
        text: "Se marcará como inactivo",
        icon: "warning",
        buttons: true,
        dangerMode: true
    })
    .then((ok) => {

        if (ok) {

            $.ajax({
                url: "/mi_proyecto/modulos/usuarios/usuariosModelo.php?option=eliminar&id=" + id,
                type: "POST",
                dataType: "json",

                success: function (r) {

                    if (r.exito == 1) {
                        swal("Éxito", "Usuario eliminado", "success")
                            .then(() => { location.reload(); });
                    } else {
                        swal("Error", "No se pudo eliminar el usuario", "error");
                    }

                },

                error: function (xhr) {
                    console.log("ERROR ELIMINAR:", xhr.responseText);
                    swal("Error", "Error en servidor al eliminar", "error");
                }
            });

        }

    });

}