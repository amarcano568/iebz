swChat = false;

function deleteFile(fileDelete) {
    setTimeout(function () {
        $.ajax({
            url: "delete-file-pdf",
            type: "get",
            data: {
                fileDelete: fileDelete,
            },
            beforeSend: function () {},
        })
            .done(function (data) {
                console.log(data);
            })
            .fail(function (statusCode, errorThrown) {
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });
    }, 5000);
}

function selectedOptionMenu(id, clase, subId = "", navlink = "") {
    if (subId != "") {
        // $(".nav-link").attr('class', 'nav-link');
        $("#" + subId).attr("class", navlink);
    }

    $(".br-menu-link").attr("class", "br-menu-link");
    $("#" + id).attr("class", clase);
}

function existeFecha(fecha) {
    var fechaf = fecha.split("/");
    var day = fechaf[0];
    var month = fechaf[1];
    var year = fechaf[2];
    var date = new Date(year, month, "0");
    if (day - 0 > date.getDate() - 0) {
        return false;
    }
    return true;
}

function calcularEdad(birthday) {
    console.log(birthday);
    var birthday_arr = birthday.split("-");
    var birthday_date = new Date(
        birthday_arr[0],
        birthday_arr[1] - 1,
        birthday_arr[2]
    );
    var ageDifMs = Date.now() - birthday_date.getTime();
    var ageDate = new Date(ageDifMs);
    edad = Math.abs(ageDate.getUTCFullYear() - 1970);

    return edad === NaN ? "" : edad;
}

function loadingUI(message, color) {
    $.blockUI({
        baseZ: 2000,
        css: {
            border: "none",
            padding: "15px",
            backgroundColor: color,
            "-webkit-border-radius": "10px",
            "-moz-border-radius": "10px",
            //opacity                  : 0.5,
            color: "#003465",
            //width                    : '40em'
        },
        message:
            '<h2><i class="fas fa-spinner fa-pulse"></i> <span class="hidden-xs">' +
            message +
            "</span></h2>",
    });
}

function responseUI(message, color) {
    $.blockUI({
        baseZ: 2000,
        css: {
            border: "none",
            padding: "15px",
            backgroundColor: color,
            "-webkit-border-radius": "10px",
            "-moz-border-radius": "10px",
            opacity: 0.5,
            color: "#fff",
        },
        message: '<h2 class="blockUIMensaje">' + message + "</h2>",
    });

    setTimeout(function () {
        $.unblockUI();
    }, 2000);
}

// $(".nav-link").click(function(event) {

// })

function destroy_existing_data_table(tableDestry) {
    var existing_table = $(tableDestry).dataTable();
    if (existing_table != undefined) {
        existing_table.fnClearTable();
        existing_table.fnDestroy();
    }
}

function ajaxError(statusCode, errorThrown) {
    if (statusCode.status == 0) {
        alertify.alert(
            "Alerta...",
            '<h4 class="yellow">Internet: Problemas de Conexion</h4>',
            function () {
                alertify.success("Ok");
            }
        );
    } else if (statusCode.status == 422) {
        console.warn(statusCode.responseJSON.errors);
        $(".errorDescripcion").remove();
        $.each(statusCode.responseJSON.errors, function (i, error) {
            var el = $(document).find('[name="' + i + '"]');
            console.log(el);
            el.after(
                $(
                    '<p class="errorDescripcion" style="color: #a94442;background-color: #f2dede;border-color: #ebccd1;padding:1px 20px 1px 20px;">' +
                        error[0] +
                        "</p>"
                )
            );
        });
    } else {
        console.log(statusCode);
        console.log(errorThrown);
        alertify.alert(
            "Alerta...",
            '<h4 class="text-danger"><i class="text-danger fas fa-exclamation-triangle"></i> Error del Sistema</h4>',
            function () {
                alertify.success("Ok");
            }
        );
    }
}

function changeSwitchery(element, checked) {
    if (
        (element.is(":checked") && checked == false) ||
        (!element.is(":checked") && checked == true)
    ) {
        element.parent().find(".switchery").trigger("click");
    }
}

function Pnotifica(title1, text1, type1, hide1) {
    new PNotify({
        title: title1,
        text: text1,
        type: type1,
        hide: hide1,
        addClass: "translucent",
        styling: "bootstrap3",
    });
}

function ConfigChosen() {
    return {
        no_results_text: "No hay resultados para ",
        placeholder_text_single: "Seleccione una Opción",
        disable_search_threshold: 10,
        width: "100%",
        allow_single_deselect: true,
    };
}

$(function () {
    function initToolbarBootstrapBindings() {
        var fonts = [
                "Serif",
                "Sans",
                "Arial",
                "Arial Black",
                "Courier",
                "Courier New",
                "Comic Sans MS",
                "Helvetica",
                "Impact",
                "Lucida Grande",
                "Lucida Sans",
                "Tahoma",
                "Times",
                "Times New Roman",
                "Verdana",
            ],
            fontTarget = $("[title=Font]").siblings(".dropdown-menu");
        $.each(fonts, function (idx, fontName) {
            fontTarget.append(
                $(
                    '<li><a data-edit="fontName ' +
                        fontName +
                        '" style="font-family:\'' +
                        fontName +
                        "'\">" +
                        fontName +
                        "</a></li>"
                )
            );
        });
        $("a[title]").tooltip({
            container: "body",
        });
        $(".dropdown-menu input")
            .click(function () {
                return false;
            })
            .change(function () {
                $(this)
                    .parent(".dropdown-menu")
                    .siblings(".dropdown-toggle")
                    .dropdown("toggle");
            })
            .keydown("esc", function () {
                this.value = "";
                $(this).change();
            });

        $("[data-role=magic-overlay]").each(function () {
            var overlay = $(this),
                target = $(overlay.data("target"));
            overlay
                .css("opacity", 0)
                .css("position", "absolute")
                .offset(target.offset())
                .width(target.outerWidth())
                .height(target.outerHeight());
        });
        if ("onwebkitspeechchange" in document.createElement("input")) {
            var editorOffset = $("#editor").offset();
            $("#voiceBtn")
                .css("position", "absolute")
                .offset({
                    top: editorOffset.top,
                    left: editorOffset.left + $("#editor").innerWidth() - 35,
                });
        } else {
            $("#voiceBtn").hide();
        }
    }

    function showErrorAlert(reason, detail) {
        var msg = "";
        if (reason === "unsupported-file-type") {
            msg = "Unsupported format " + detail;
        } else {
            console.log("error uploading file", reason, detail);
        }
        $(
            '<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
                "<strong>File upload error</strong> " +
                msg +
                " </div>"
        ).prependTo("#alerts");
    }
    initToolbarBootstrapBindings();
    $("#editor").wysiwyg({
        fileUploadError: showErrorAlert,
    });
    window.prettyPrint && prettyPrint();
});

function enviarCorreo(ticketNroIn, tipoIn) {
    $.ajax({
        url: "enviar-correo",
        type: "get",
        data: {
            ticketNro: ticketNroIn,
            tipo: tipoIn,
        },
        // beforeSend: function() {
        //     loadingUI('Actualizando');
        // }
    })
        .done(function (data) {
            console.log(data);

            //$.unblockUI();
        })
        .fail(function (statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
}

$(document).on("click", "#pruebaCorreo", function (event) {
    event.preventDefault();
    enviarCorreo(6, 4);
});

/* Ventana de Chat */
$(document).on("click", "#BtnEventoTicket99", function (event) {
    $("#ModalChat").modal("show");
    $("#inputMensaje").focus();
    var nroTicket = $("#TituloNroTicket").text();
    swChat = true;
    ListarConversacion(nroTicket);
});

$("#ModalChat").on("hidden.bs.modal", function (e) {
    swChat = false;
});

function ListarConversacion(nroTicket) {
    $.ajax({
        url: "listar-mensaje-ticket",
        type: "get",
        dataType: "json",
        data: {
            nroTicket: nroTicket,
            _token: "{{ csrf_token() }}",
        },
    })
        .done(function (response) {
            console.log(response.data);
            $(".chat").html(response.data);
        })
        .fail(function (statusCode, errorThrown) {
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
}

$(document).on("click", "#btn-chat", function (event) {
    event.preventDefault();
    var nroTicket = $("#TituloNroTicket").text();
    msg = $("#inputMensaje").val();
    if (msg == "") {
        Pnotifica(
            "Enviar mensaje.",
            "Debe escribir el mensaje para la consulta.",
            "warning",
            true
        );
        $("#inputMensaje").focus();
        return false;
    }

    $.ajax({
        url: "enviar-mensaje-ticket",
        type: "get",
        dataType: "json",
        data: {
            nroTicket: nroTicket,
            mensaje: msg,
            _token: "{{ csrf_token() }}",
        },
        // beforeSend: function() {
        //     loadingUI('Anulando Ticket..!');
        // }
    })
        .done(function (response) {
            console.log(response.data);
            ListarConversacion(nroTicket);
            $("#inputMensaje").val("");
        })
        .fail(function (statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
});

$("#inputMensaje").keypress(function (e) {
    if (e.keyCode == 13) $("#btn-chat").click();
});

setInterval(function () {
    if (swChat === true) {
        var nroTicket = $("#TituloNroTicket").text();
        ListarConversacion(nroTicket);
    }
}, 5000); // Ejecuta la Función cada 30 seg.

$(document).on("click", "#modoUsuario", function (event) {
    event.preventDefault();
    modoUsuario();
});

userCayro = $("#userCayro").val();
userEmpresa = $("#userEmpresa").val();
baseDatosEmpresa = $("#baseDatosEmpresa").val();

function modoUsuario() {
    alertify
        .confirm(
            "Tipo de Usuario",
            '<h5 class="text-info">Seleccione el modo de interactuar con la aplicación.</h5>',
            function () {
                Pnotifica(
                    "Usuario elegido.",
                    "Usted estara interactuando como " + userEmpresa,
                    "success",
                    true
                );
                $(".alert").show();
                defaultBaseDatos(baseDatosEmpresa);
            },
            function () {
                Pnotifica(
                    "Usuario elegido.",
                    "Usted estara interactuando como Usuario Cayro.",
                    "success",
                    true
                );
                $(".alert").show();
                defaultBaseDatos("cayro");
            }
        )
        .set("labels", {
            ok: userEmpresa,
            cancel: "Usuario Cayro",
        })
        .set({
            transition: "zoom",
        })
        .set({
            modal: true,
            closableByDimmer: false,
        });
}

function defaultBaseDatos(baseDatosEmpresa) {
    $.ajax({
        url: "default-base-datos",
        type: "get",
        data: {
            baseDatos: baseDatosEmpresa,
            _token: "{{ csrf_token() }}",
        },
        // beforeSend: function() {
        //     loadingUI('Actualizando');
        // }
    })
        .done(function (data) {
            console.log(data);
            //window.location.href = "/";
            window.location.href = "/helpdesk/public/login";
            //CargaTablero();
            // $.unblockUI();
        })
        .fail(function (statusCode, errorThrown) {
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
}

$(document).on("click", "#btnRetornar", function (event) {
    event.preventDefault();
    window.location.href = "/mantUsuarios";
});
