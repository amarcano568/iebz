$(document).on("ready", function () {
    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = "";
    var descripcionImg = "";
    var objetoDataTables_Miembros = "";

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";

    $(".chosen-select").chosen(ConfigChosen());

    listarCumpleanos();

    $(document).on("change", "#mes", function (event) {
        listarCumpleanos();
    });

    $(document).on("change", "#iglesia", function (event) {
        listarCumpleanos();
    });

    function listarCumpleanos() {
        mes = $("#mes").val();
        iglesia = $("#iglesia").val();
        nombreMes = $("#mes option:selected").text();
        $.ajax({
            url: "listar-cumpleanos",
            type: "get",
            data: {
                mes: mes,
                iglesia: iglesia,
                nombreMes: nombreMes,
            },
            beforeSend: function () {
                loadingUI("Generando informe de Cumpleaños");
            },
        })
            .done(function (data) {
                console.log(data);
                $.unblockUI();

                $("#listado-de-cumpleanos-mes").html(
                    '<iframe id="ObjPdf" src="" width="100%" height="500" type="application/pdf"></iframe> '
                );
                $("#ObjPdf").attr("src", data);
                deleteFile(data);
            })
            .fail(function (statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });
    }
});
