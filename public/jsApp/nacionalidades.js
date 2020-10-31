$(document).on("ready", function () {
    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = "";
    var descripcionImg = "";
    var objetoDataTables_Miembros = "";

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";

    $(".chosen-select").chosen(ConfigChosen());

    listarNacionalidades();

    $(document).on("change", "#idIglesia", function (event) {
        listarNacionalidades();
    });

    $(document).on("change", "#status", function (event) {
        listarNacionalidades();
    });

    function listarNacionalidades() {
        idIglesia = $("#idIglesia").val();
        status = $("#status").val();
        nombreStatus = $("#status option:selected").text();

        $.ajax({
            url: "listar-nacionalidades",
            type: "get",
            data: {
                idIglesia: idIglesia,
                status: status,
                nombreStatus: nombreStatus,
            },
            beforeSend: function () {
                loadingUI("Generando informe de Nacionalidades");
            },
        })
            .done(function (data) {
                console.log(data);
                $.unblockUI();

                $("#listado-nacionalidades").html(
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
