$(document).on("ready", function () {
    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = "";
    var descripcionImg = "";
    var objetoDataTables_Miembros = "";

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";

    $(".chosen-select").chosen(ConfigChosen());

    listarMinisterios();

    $(document).on("change", "#idMinisterio", function (event) {
        listarMinisterios();
    });

    function listarMinisterios() {
        idMinisterio = $("#idMinisterio").val();
        nombreMinisterio = $("#idMinisterio option:selected").text();

        $.ajax({
            url: "listar-informe-ministerios",
            type: "get",
            data: {
                idMinisterio: idMinisterio,
                nombreMinisterio: nombreMinisterio,
            },
            beforeSend: function () {
                loadingUI("Generando informe de Ministerios");
            },
        })
            .done(function (data) {
                console.log(data);
                $.unblockUI();

                $("#listado-de-ministerios").html(
                    '<iframe id="ObjPdf" src="" width="100%" height="600" type="application/pdf"></iframe> '
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
