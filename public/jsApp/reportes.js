$(document).on('ready', function() {

    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = '';
    var descripcionImg = '';
    var objetoDataTables_Tickets = '';
    var obj = '';
    var Pendiente = '';
    var Asignado = '';
    var Anulado = '';
    var Reasignado = '';
    var Iniciado = '';
    var Reaperturado = '';
    var Pausado = '';
    var Resuelto = '';
    var Cerrado = '';

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";
    $('.chosen-select').chosen(ConfigChosen());

    listarMaestroTickets();

    function listarMaestroTickets() {
        var idIuser = $('#usuarios').val();
        var grupo = $('#grupo').val();
        var desde = $('#reportrange').data('daterangepicker').startDate.format('DD/MM/YYYY');
        var hasta = $('#reportrange').data('daterangepicker').endDate.format('DD/MM/YYYY');
        rangoFecha = desde + ' - ' + hasta;
        var groupColumn = 0;
        $.fn.dataTable.ext.buttons.graficos = {
            className: 'buttons-graficos',
            action: function(e, dt, node, config) {
                $("#ModalGrafico").modal('show');
            }
        };
        destroy_existing_data_table('#TableTickets');
        objetoDataTables_Tickets = $('#TableTickets').DataTable({
            responsive: true,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(groupColumn, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="6" style="text-align:center;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });
            },

            dom: 'Bfrtip',
            buttons: [{
                extend: 'copyHtml5',
                title: '',
                messageTop: null,
                text: '<i class="far fa-copy"></i> Copiar '
            }, {
                extend: 'excel',
                title: '',
                messageTop: null,
                text: '<i class="far fa-file-excel"></i> Excel ',
                exportOptions: {
                    //columns: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 14, 15, 22, 23, 24]
                },
                messageTop: 'Listado de Tickets Enviados. (' + rangoFecha + ')'
            }, {
                extend: 'csvHtml5',
                text: '<i class="fas fa-file-csv"></i> CSV '
            }, {
                extend: 'pdf',
                text: '<i class="far fa-file-pdf"></i> Pdf',
                exportOptions: {
                    // modifier: {
                    //     page: 'current'
                    // }
                }
            }, {
                extend: 'graficos',
                text: '<i class="fas fa-chart-area"></i> Gráfico'
            }],
            "paginationType": "input",
            "sPaginationType": "full_numbers",
            "language": {
                "buttons": {
                    "copyTitle": '<i class="far fa-copy"></i> Tickets copiados al Portapales',
                    "copySuccess": {
                        _: '%d Tickets copiados.',
                        1: '1 Ticket copiado.'
                    }
                },
                "searchPlaceholder": "Buscar",
                "sProcessing": "Procesando...",
                "sLengthMenu": " _MENU_ Tickets",
                "sZeroRecords": "No se encontró ninguna Empresa con la Condición del Filtro",
                "sEmptyTable": "Ninguna Empresa Agregado aún...",
                "sInfo": "Del _START_ al _END_ de un total de _TOTAL_ Tickets",
                "sInfoEmpty": "De 0 al 0 de un total de 0 Tickets",
                "sInfoFiltered": "(filtrado de un total de _MAX_ Tickets)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": loadingUI('Procesando...', 'white'),
                "oPaginate": {
                    "sFirst": '<i class="fas fa-angle-double-left"></i>',
                    "sLast": '<i class="fas fa-angle-double-right"></i>',
                    "sNext": '<i class="fas fa-angle-left"></i>',
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
            "iDisplayLength": 25,
            "initComplete": function(settings, json) {
                $.unblockUI();
                $('[data-toggle="popover"]').popover();
            },
            "ajax": {
                "method": "get",
                "url": "listados-tickets-enviados",
                "data": {
                    'idIuser': idIuser,
                    'grupo': grupo,
                    'desde': desde,
                    'hasta': hasta
                }
            },
            "columns": [{
                "data": 0
            }, {
                "data": 1,
            }, {
                "data": 2,
                "className": 'dt-center'
            }, {
                "data": 3,
                "className": 'dt-center'
            }, {
                "data": 4,
            }, {
                "data": 5,
            }, {
                "data": 6,
            }],
            "columnDefs": [{
                "width": "5%",
                "targets": [0]
            }, {
                "width": "20%",
                "targets": [
                    2, 3
                ]
            }, {
                "width": "40%",
                "targets": [
                    4
                ]
            }, {
                "width": "15%",
                "targets": [
                    4, 5
                ]
            }]
        });

        objetoDataTables_Tickets.columns(0).visible(false);
    }


    $(document).on('change', '#usuarios', function(event) {
        listarMaestroTickets();
    });

    $(document).on('change', '#grupo', function(event) {
        listarMaestroTickets();
    });

    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        listarMaestroTickets();
    });

    $('#ModalGrafico').on('show.bs.modal', function() {
        var idIuser = $('#usuarios').val();
        var grupo = $('#grupo').val();
        var desde = $('#reportrange').data('daterangepicker').startDate.format('DD/MM/YYYY');
        var hasta = $('#reportrange').data('daterangepicker').endDate.format('DD/MM/YYYY');

        $.ajax({
            url: 'grafico-reporte-ticket-recibido',
            type: 'get',
            data: {
                'idIuser': idIuser,
                'grupo': grupo,
                'desde': desde,
                'hasta': hasta
            },
            beforeSend: function() {
                loadingUI('Cargando gráfico...');
            }
        }).done(function(response) {

            console.log(response);
            obj = response.data;
            $.each(obj, function(key, value) {
                if (value.estado == 1) { // Pendiente
                    Pendiente = value.cantidad;
                } else if (value.estado == 2) { // Asignado
                    Asignado = value.cantidad;
                } else if (value.estado == 3) { // Anulado
                    Anulado = value.cantidad;
                } else if (value.estado == 4) { // Reasignado
                    Reasignado = value.cantidad;
                } else if (value.estado == 5) { // Iniciado
                    Iniciado = value.cantidad;
                } else if (value.estado == 6) { // Reaperturado
                    Reaperturado = value.cantidad;
                } else if (value.estado == 7) { // Pausado
                    Pausado = value.cantidad;
                } else if (value.estado == 8) { // Resuelto
                    Resuelto = value.cantidad;
                } else if (value.estado == 9) { // Cerrado
                    Cerrado = value.cantidad;
                }
            });

            $("#btnGraphBar").click();

            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
    })

    $("#btnGraphBar").click(function(event) {
        $("#idGrafico").html('');
        Morris.Bar({
            element: 'idGrafico',
            data: [{
                device: 'Pendiente',
                Ticket: Pendiente
            }, {
                device: 'Asignado',
                Ticket: Asignado
            }, {
                device: 'Anulado',
                Ticket: Anulado
            }, {
                device: 'Reasignado',
                Ticket: Reasignado
            }, {
                device: 'Iniciado',
                Ticket: Iniciado
            }, {
                device: 'Reaperturado',
                Ticket: Reaperturado
            }, {
                device: 'Pausado',
                Ticket: Pausado
            }, {
                device: 'Resuelto',
                Ticket: Resuelto
            }, {
                device: 'Cerrado',
                Ticket: Cerrado
            }],
            xkey: 'device',
            ykeys: ['Ticket'],
            labels: ['Ticket'],
            barRatio: 0.4,
            barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
            xLabelAngle: 35,
            hideHover: 'auto',
            resize: true
        });
    });

    $("#btnGraphPie").click(function(event) {
        $("#idGrafico").html('');
        Morris.Donut({
            element: 'idGrafico',
            data: [{
                label: "Pendiente",
                value: Pendiente
            }, {
                label: "Asignado",
                value: Asignado
            }, {
                label: "Anulado",
                value: Anulado
            }, {
                label: "Reasignado",
                value: Reasignado
            }, {
                label: "Iniciado",
                value: Iniciado
            }, {
                label: "Reaperturado",
                value: Reaperturado
            }, {
                label: "Pausado",
                value: Pausado
            }, {
                label: "Resuelto",
                value: Resuelto
            }, {
                label: "Cerrado",
                value: Cerrado
            }]
        })
    });

});