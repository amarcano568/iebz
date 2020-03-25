$(document).on('ready', function() {

    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = '';
    var descripcionImg = '';
    var objetoDataTables_Miembros = '';

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";


    $(".chosen-select").chosen(ConfigChosen());

    informeMiembros();

    $(document).on('change', '#idIglesia', function(event) {
        informeMiembros();
    });

    $(document).on('change', '#status', function(event) {
        informeMiembros();
    });



    function informeMiembros() {
        idIglesia = $("#idIglesia").val();
        status = $("#status").val();
    
        $.ajax({
            url: 'listado-miembros',
            type: 'get',
            data: {
                idIglesia: idIglesia,
                status: status
            },
            beforeSend: function() {
                loadingUI('Generando Informe de Miembros');
            }
        }).done(function(data) {
            console.log(data)
            $.unblockUI();
            
            $("#listado-de-miembros").html('<iframe id="ObjPdf" src="" width="100%" height="600" type="application/pdf"></iframe> ');
            $('#ObjPdf').attr('src', data);
        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
    }



});
