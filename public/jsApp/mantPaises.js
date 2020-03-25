$(document).on('ready', function() {

    objetoDataTables_Paises = $('#tablePaises');

    $("#status").chosen(ConfigChosen());

    listarPaises();

    function listarPaises() {
        destroy_existing_data_table('#tablePaises');
        $.fn.dataTable.ext.pager.numbers_length = 4;
        objetoDataTables_Paises = $('#tablePaises').DataTable({
            "order": [
                [1, "asc"]
            ],
            dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            //"dom": '<"top"i>rt<"bottom"flp><"clear">',
            "paginationType": "input",
            "sPaginationType": "full_numbers",
            "language": {
                "searchPlaceholder": "Buscar",
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ Paises",
                "sZeroRecords": "No se encontró ningun Pais con la Condición del Filtro",
                "sEmptyTable": "Ningun Pais Agregado aún...",
                "sInfo": "Del _START_ al _END_ de un total de _TOTAL_ Pais",
                "sInfoEmpty": "De 0 al 0 de un total de 0 Pais",
                "sInfoFiltered": "(filtrado de un total de _MAX_ Paises)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": loadingUI('Cargando listado de Paises...', 'white'),
                "oPaginate": {
                    "sFirst": '<i class="fas fa-angle-double-left"></i>',
                    "sLast": '<i class="fas fa-angle-double-right"></i>',
                    "sNext": '<i class="fas fa-angle-right"></i>',
                    "sPrevious": '<i class="fas fa-angle-left"></i>',
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "lengthMenu": [
                [5, 10, 20, 25, 50, -1],
                [5, 10, 20, 25, 50, "Todos"]
            ],
            "iDisplayLength": 10,
            "ajax": {
                "method": "get",
                "url": "listar-paises",
                "data": {

                },
            },
            "initComplete": function(settings, json) {
                $.unblockUI();


            },
            "columns": [{
                "data": 0
            }, {
                "data": 1
            }, {
                "data": 2
            }],
            "columnDefs": [{
                    className: "dt-head-center",
                    targets: [2, 3]
                },
                {
                    "width": "10%",
                    "targets": 0
                }, {
                    "width": "65%",
                    "targets": 1
                }, {
                    "width": "25%",
                    "targets": 2,
                    "className": "text-center"
                }

            ],

        });

    }


    jQuery.validator.setDefaults({
        errorClass: 'help-block',
        focusInvalid: true,
        highlight: function(element) {
            $(element).removeClass('is-valid').addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        errorPlacement: function(error, element) {
            if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $.validator.setDefaults({
        ignore: ":hidden:not(.chosen-select)"
    })

    $("#FormMantPais").validate({
        rules: {
            nombre: {
                required: true,
                minlength: 2
            }

        },
        messages: {
            nombre: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Debe introducir Descripción para el País.'
        },
        submitHandler: function(form) {

            var form = $('#FormMantPais');
            var formData = form.serialize();
            var route = form.attr('action');

            $.ajax({
                url: route,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    loadingUI('Actualizando País');
                }
            }).done(function(data) {


                $('#tablePaises').DataTable().ajax.reload();
                $("#ModalMantPais").modal('hide');
                if (data.success === true) {
                    Pnotifica('Pais', data.mensaje, 'success', true);
                } else {
                    Pnotifica('Pais', data.mensaje, 'error', true);
                }

                $.unblockUI();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });


            $("#ModalMantPais").modal('hide');
            $('#FormMantPais').each(function() {
                this.reset();
            });
        }
    })

})

$(document).on('click', '#BtnNuevo', function(event) {
    event.preventDefault();
    $('#FormMantPais').each(function() {
        this.reset();
    });
    $("#tituloModal").html('<i class="fas fa-globe-europe"></i> Nueva Pais.')
    $("#ModalMantPais").modal('show');
});



$('body').on('click', '#bodyTablePaises a', function(e) {
    e.preventDefault();

    accion_ok = $(this).attr('data-accion');
    idPais = $(this).attr('idPais');

    switch (accion_ok) {
        case "inactivar":
            activaInactivaPais(idPais);
            break;
        case "activar":
            activaInactivaPais(idPais);
            break;
        case 'editarPais': // Edita Pais
            loadingUI('Cargando datos para editar...', 'white')
            $.ajax({
                url: 'editar-pais',
                type: 'get',
                datatype: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    idPais: idPais
                }
            }).fail(function(statusCode, errorThrown) {
                console.log(statusCode + ' ' + errorThrown);
            }).done(function(response) {

                console.log(response.archivosAdjuntos)

                $("#ModalMantPais").modal('show');
                $("#tituloModal").html('<i class="fas fa-globe-europe"></i> Editar datos del Pais.')

                $("#idPais").val(response.data.id);
                $("#nombre").val(response.data.nombre);
                $("#status").val(response.data.status).trigger("chosen:updated");

                $.unblockUI();

            })

            break;

    }
});

function activaInactivaPais(idPais) {

    loadingUI('Actualizando status...', 'white')
    $.ajax({
        url: 'actualizar-status-pais',
        type: 'get',
        datatype: 'json',
        data: {
            _token: '{{ csrf_token() }}',
            idPais: idPais
        }
    }).fail(function(statusCode, errorThrown) {
        console.log(statusCode + ' ' + errorThrown);
    }).done(function(response) {

        console.log(response)
        objetoDataTables_Paises.ajax.reload();
        $.unblockUI();

    })
}
