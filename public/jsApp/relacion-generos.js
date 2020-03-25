$(document).on('ready', function() {

    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = '';
    var descripcionImg = '';
    var objetoDataTables_Miembros = '';

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";

    listarMiembros();

    function listarMiembros() {
        destroy_existing_data_table('#tableMiembros');
        $.fn.dataTable.ext.pager.numbers_length = 4;
        objetoDataTables_Miembros = $('#tableMiembros').DataTable({
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
                "sLengthMenu": "Mostrar _MENU_ Miembros",
                "sZeroRecords": "No se encontró ningun Miembro con la Condición del Filtro",
                "sEmptyTable": "Ningun Miembro Agregado aún...",
                "sInfo": "Del _START_ al _END_ de un total de _TOTAL_ Miembro",
                "sInfoEmpty": "De 0 al 0 de un total de 0 Miembro",
                "sInfoFiltered": "(filtrado de un total de _MAX_ Miembros)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": loadingUI('Cargando listado de Miembros...', 'white'),
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
                "url": "listar-miembros-generos",
                "data": {

                },
            },
            "initComplete": function(settings, json) {
                $.unblockUI();
                $('[data-toggle="popover"]').popover()
            },
            "columns": [{
                "data": 0
            }, {
                "data": 1
            }, {
                "data": 2
            }, {
                "data": 3
            }, {
                "data": 4
            }, {
                "data": 5
            }],
            "columnDefs": [{
                    className: "dt-head-center",
                    targets: [0, 1, 2, 3, 4]
                },
                {
                    "width": "5%",
                    "targets": 0
                }, {
                    "width": "25%",
                    "targets": 1
                }, {
                    "width": "25%",
                    "targets": 2
                }, {
                    "width": "25%",
                    "targets": 3
                }, {
                    "width": "10%",
                    "targets": 4
                }, {
                    "width": "10%",
                    "targets": 5,
                    "className": "text-center"
                }
            ],

        });

    }

    $('body').on('click', '#body-miembros a', function(e) {
        e.preventDefault();

        accion_ok = $(this).attr('data-accion');
        idMiembro = $(this).attr('idMiembro');

        switch (accion_ok) {

            case 'Masculino': // Edita Usuario
                asignarGenero(accion_ok, idMiembro);
                break;
            case 'Femenino': // Edita Usuario
                asignarGenero(accion_ok, idMiembro);
                break;

        }
    });


    function asignarGenero(genero, idMiembro) {
        loadingUI('Asignando genero al Miembro...', 'white')
        $.ajax({
            url: 'asignar-genero',
            type: 'get',
            datatype: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                idMiembro: idMiembro,
                genero: genero
            }
        }).fail(function(statusCode, errorThrown) {
            console.log(statusCode + ' ' + errorThrown);
        }).done(function(response) {

            console.log(response.archivosAdjuntos)
            objetoDataTables_Miembros.ajax.reload();
            $.unblockUI();

        })
    }



});
