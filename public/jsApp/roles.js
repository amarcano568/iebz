$(document).on('ready', function() {


    /*
      Eventos Click sobre la Table <Tciket> para ver detalles.
     */
    $('body').on('click', '#body-roles a', function(e) {
        e.preventDefault();

        accion_ok = $(this).attr('data-accion');
        idRole = $(this).attr('idRole');
        url = $(this).attr('urlRole');

        if (accion_ok == "ver-role") {

            $('#ModalVerRole').modal('show');
            $.ajax({
                url: 'role.buscar',
                type: 'get',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    idRole: idRole
                },
                beforeSend: function() {
                    loadingUI('Buscando role');
                }
            }).done(function(response) {

                console.log(response)
                $('#roleNombre').val(response.data.name);
                $('#roleSlug').val(response.data.slug);
                $('#roleDescripcion').val(response.data.description);
                $.unblockUI();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });
        } else if (accion_ok == "editar-role") {
            window.location.href = url;
        }

    });

    // $(document).on('click', '#btnRetornar', function(event) {
    //     event.preventDefault();
    //     window.location.href = '/roles';
    // });

});