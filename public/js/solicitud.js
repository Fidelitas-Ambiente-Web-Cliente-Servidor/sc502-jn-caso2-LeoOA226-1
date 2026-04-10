
$(function () {
  
    const urlBase = "index.php";

    function mostrarMensaje(texto, tipo) {
        let mensaje = $("#mensaje");
        mensaje.removeClass("alert alert-success alert-danger");

        if (tipo === "success") {
            mensaje.addClass("alert alert-success");
        } else {
            mensaje.addClass("alert alert-danger");
        }

        mensaje.text(texto).show();
    }


    function getTalleres() {
        $.get(urlBase + "?option=talleres_json", function (data, status) {
            if (typeof data === "string") {
                data = JSON.parse(data);
            }

            let listaTalleres = $("#listaTalleres");
            listaTalleres.html("");

            if (data.length === 0) {
                listaTalleres.append("<tr><td colspan='5'>No hay talleres disponibles</td></tr>");
            } else {
                data.forEach(function (element) {
                    listaTalleres.append(
                        "<tr>" +
                        "<td>" + element.nombre + "</td>" +
                        "<td>" + element.descripcion + "</td>" +
                        "<td>" + element.cupo_maximo + "</td>" +
                        "<td>" + element.cupo_disponible + "</td>" +
                        "<td><button class='btn btn-primary btnSolicitar' data-id='" + element.id + "'>Solicitar</button></td>" +
                        "</tr>"
                    );
                });
            }
        });
    }

    $(document).on("click", ".btnSolicitar", function () {
        let tallerId = $(this).data("id");

        $.post(urlBase,
            {
                option: "solicitar",
                taller_id: tallerId
            },
            function (data, status) {
                if (typeof data === "string") {
                    data = JSON.parse(data);
                }

                if (data.success) {
                    mostrarMensaje(data.message, "success");
                    getTalleres();
                } else {
                    mostrarMensaje(data.error, "error");
                }
            }
        );
    });

    $("#btnLogout").on("click", function () {
        $.post(urlBase,
            {
                option: "logout"
            },
            function (data, status) {
                if (typeof data === "string") {
                    data = JSON.parse(data);
                }

                if (data.response === "00") {
                    window.location.href = "index.php?page=login";
                }
            }
        );
    });

    getTalleres();
});
