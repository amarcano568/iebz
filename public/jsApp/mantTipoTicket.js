$(document).on('ready', function() {
    objetoDataTables_personal = $('#tablePrincipal').DataTable();

    /* Deshabilito la tecla <Enter> para el uso de formularios */
    function destroy_existing_data_table(tableDestry) {
        var existing_table = $(tableDestry).dataTable();
        if (existing_table != undefined) {
            existing_table.fnClearTable();
            existing_table.fnDestroy();
        }
    }

    $('.chosen-select').chosen(ConfigChosen());

    muestraDataTables();

    function destroy_existing_data_table(tableDestry) {
        var existing_table = $(tableDestry).dataTable();
        if (existing_table != undefined) {
            existing_table.fnClearTable();
            existing_table.fnDestroy();
        }
    }

    function muestraDataTables() {

        destroy_existing_data_table('#tablePrincipal');
        $.fn.dataTable.ext.pager.numbers_length = 4;
        objetoDataTables_personal = $('#tablePrincipal').DataTable({
            "order": [
                [1, "desc"]
            ],
            dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            //"dom"                                : '<"top"i>rt<"bottom"flp><"clear">',
            "paginationType": "input",
            "sPaginationType": "full_numbers",
            "language": {
                "searchPlaceholder": "Buscar",
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ Registros",
                "sZeroRecords": "No se encontró ningun Registro con la Condición del Filtro",
                "sEmptyTable": "Ningun Registro Agregado aún...",
                "sInfo": "Del _START_ al _END_ de un total de _TOTAL_ Registro",
                "sInfoEmpty": "De 0 al 0 de un total de 0 Registro",
                "sInfoFiltered": "(filtrado de un total de _MAX_ Registros)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": loadingUI('Procesando...', 'white'),
                "oPaginate": {
                    "sFirst": "<<",
                    "sLast": ">>",
                    "sNext": ">",
                    "sPrevious": "<",
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
                "url": "carga-tipos-tickets",
                "data": {

                }
            },
            "initComplete": function(settings, json) {
                $.unblockUI();
                $('[data-toggle="popover"]').popover();
                objetoDataTables_personal.buttons().container().appendTo('#example_wrapper .col-sm-6:eq(0)');
            },
            "columns": [{
                "className": 'celda_de_descripcion',
                "orderable": false,
                "data": null,
                "defaultContent": '<a class="botonesGraficos" href=""><i class="bigger-140 fa fa-plus-circle text-success" aria-hidden="true"></i></a>'
            }, {
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
                "width": "5%",
                "targets": 0
            }, {
                "width": "10%",
                "targets": 1
            }, {
                "width": "5%",
                "targets": 2
            }, {
                "width": "45%",
                "targets": 3
            }, {
                "width": "15%",
                "targets": 4
            }, {
                "width": "10%",
                "targets": 5
            }, {
                "width": "10%",
                "targets": 6
            }, {
                className: "text-center",
                "targets": [4]
            }],


        });
        objetoDataTables_personal.columns(1).visible(false);
        objetoDataTables_personal.columns(2).visible(false);
        objetoDataTables_personal.columns(5).visible(false);

    }

    $(document).on('click', '.botonesGraficos', function(event) {
        event.preventDefault();
    });

    $('#bodyTablePrincipal').on('click', 'td.celda_de_descripcion', function() {

        var filaDeLaTabla = $(this).closest('tr');
        var filaComplementaria = objetoDataTables_personal.row(filaDeLaTabla);
        var celdaDeIcono = $(this).closest('td.celda_de_descripcion');

        if (filaComplementaria.child.isShown()) { // La fila complementaria está abierta y se cierra.
            filaComplementaria.child.hide();
            celdaDeIcono.html('<a class="botonesGraficos" href=""><i class="bigger-140 fa fa-plus-circle text-success" aria-hidden="true"></i></a>');
        } else { // La fila complementaria está cerrada y se abre.
            filaComplementaria.child(formatearSalidaDeDatosComplementarios(filaComplementaria.data(), 5)).show();
            celdaDeIcono.html('<a class="botonesGraficos" href=""><i class="bigger-140 fa fa-minus-circle text-danger" aria-hidden="true"></i></a>');
        }


    });

    function formatearSalidaDeDatosComplementarios(filaDelDataSet, columna) {
        var cadenaDeRetorno = '';

        cadenaDeRetorno += '<div class="row">';
        cadenaDeRetorno += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
        cadenaDeRetorno += filaDelDataSet[4];
        cadenaDeRetorno += '</div>';

        return cadenaDeRetorno;
    }


    /*
            Eventos Click sobre la Table <Tciket> para ver detalles.
            */
    $('body').on('click', '#bodyTablePrincipal a', function(e) {
        e.preventDefault();

        accion_ok = $(this).attr('data-accion');
        status = $(this).attr('status');
        interno = $(this).attr('interno');
        externo = $(this).attr('externo');

        areas = $(this).attr('areas');
        tipoTicket = $(this).parents("tr").find("td")[1].innerHTML;
        idTicket = $(this).attr('idTabla');
        descTicket = $(this).attr('desTabla');

        if (accion_ok == "EditarTipo") {
            $("#ModalTipoTicket").modal('show');
            $("#tituloModal").html('<i class="far fa-edit"></i> Editar Tipo ticket');
            $("#tipo").val('TPTK');
            $("#idTipo").val(idTicket);
            $("#descTipo").val(descTicket);
            $("#statusTicket").val(status);
            $("#statusTicket").trigger("chosen:updated");
            cargaAreasAsignadas(areas);
            inter = interno == 1 ? true : false;
            exter = externo == 1 ? true : false;
            $("#interno").prop("checked", inter);
            $("#externo").prop("checked", exter);
        }

    });

    function cargaAreasAsignadas(areas) {
        $('#chosenAreas').html("");
        $("#chosenAreas").chosen().trigger('chosen:updated');
        $.ajax({
            type: "get",
            url: "llenar-chosen-areas",
            data: {
                'areas': areas
            },
            success: function(data) {
                console.log(data);
                var json = $.parseJSON(data);
                $('#chosenAreas').empty();
                $.each(json, function(key, value) {
                    $('#chosenAreas').append('<option ' + value.seleccionable + ' value="' + value.opcion + '">' + value.valor + '</option>');
                    $("#chosenAreas").trigger("chosen:updated");
                });
                $("#esperaArea").hide();
                $('#chosenAreas').chosen('destroy').chosen(configChosen(true));
                $('#divChosenAreas').show();
            }
        });
    }

    $(".chosen-select").chosen(configChosen(true));

    function configChosen(Busqueda = false) {
        return {
            disable_search_threshold: 10,
            allow_single_deselect: true,
            width: "100%",
            no_results_text: "Oops, no encontrado!",
            disable_search: Busqueda
        }
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
    $("#FormTipoTicket").validate({
        rules: {
            descTipo: {
                required: true,
                minlength: 3
            },
            statusTicket: {
                required: true
            },
            chosenAreas: {
                required: true
            }
        },
        messages: {
            descTipo: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Debe introducir Descripción para el tipo de ticket.',
            statusTicket: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Debe seleccionar el status.',
            chosenAreas: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Seleccione las Áreas para este tipo de ticket.'
        },
        submitHandler: function(form) {
            interno = $('input:checkbox[name=interno]:checked').val();
            externo = $('input:checkbox[name=externo]:checked').val();
            interno = interno == 'on' ? 1 : 0;
            externo = externo == 'on' ? 1 : 0;
            var form = $('#FormTipoTicket');
            var formData = form.serialize() + "&interno2=" + interno + "&externo2=" + externo;
            var route = form.attr('action');
            $.ajax({
                url: route,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    loadingUI('Actualizando');
                }
            }).done(function(data) {

                console.log(data)
                objetoDataTables_personal.ajax.reload(null, false);
                if (data.success === true) {
                    Pnotifica('Tipo de Ticket', data.mensaje, 'success', true);
                } else {
                    Pnotifica('Tipo de Ticket', data.mensaje, 'error', true);
                }

                $.unblockUI();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });


            $("#ModalTipoTicket").modal('hide');
            $('#FormTipoTicket').each(function() {
                this.reset();
            });
        }
    })

    $(document).on('click', '#BtnNuevo', function(event) {
        event.preventDefault();

        $("#ModalTipoTicket").modal('show');
        $('#FormTipoTicket').each(function() {
            this.reset();
        });
        $('#chosenAreas').html("");
        $("#chosenAreas").chosen().trigger('chosen:updated');
        cargaAreasAsignadas('');
        $('#tipo').val('TPTK');
        $('#idTipo').val('0');


    });


})