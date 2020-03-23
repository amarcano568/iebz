$(document).on('ready', function() {

    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = '';
    var descripcionImg = '';
    var objetoDataTables_Miembros = '';

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";

    $("#rangeEdad").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 1,
        max: 120,
        from: 10,
        to: 90,
        type: 'double',
        step: 1,
        postfix: " Años",
        grid: true,
        onStart: function (data) {
            // fired then range slider is ready
        },
        onChange: function (data) {
            // fired on every range slider update
        },
        onFinish: function (data) {
            listarRangoEdad();
        },
        onUpdate: function (data) {
            // fired on changing slider with Update method
        }
    });

    $(".chosen-select").chosen(ConfigChosen());

    listarRangoEdad();

    $(document).on('change', '#idIglesia', function(event) {
        listarRangoEdad();
    });

    $(document).on('change', '#status', function(event) {
        listarRangoEdad();
    });



    function listarRangoEdad() {
        idIglesia = $("#idIglesia").val();
        status = $("#status").val();
        rangeEdad = $( "#rangeEdad" ).val();
    
        $.ajax({
            url: 'listar-rango-edad',
            type: 'get',
            data: {
                idIglesia: idIglesia,
                status: status,
                rangeEdad: rangeEdad
            },
            beforeSend: function() {
                loadingUI('Generando reporte de Rango de Edades');
            }
        }).done(function(data) {
            console.log(data)
            $.unblockUI();
            
            $("#listado-de-rango-edad").html('<iframe id="ObjPdf" src="" width="100%" height="600" type="application/pdf"></iframe> ');
            $('#ObjPdf').attr('src', data);
        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
    }



});
