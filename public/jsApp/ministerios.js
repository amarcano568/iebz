$(document).on('ready', function() {

    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = '';
    var descripcionImg = '';
    var objetoDataTables_Ministerios = '';

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";

    $('.chosen-select').chosen(ConfigChosen());

    crearOrgranigrama();

    function crearOrgranigrama() {
        $.ajax({
            url: 'crear-organigrama',
            type: 'get',
            data: {},
            beforeSend: function() {
                loadingUI('Generando organigrama de los Ministerios.');
            }
        }).done(function(response) {
            console.log(response)
            $.unblockUI();
            $("#organigrama").html(response.data);

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
    }

    listarMinisterios();

    function listarMinisterios() {
        destroy_existing_data_table('#tableMinisterios');
        $.fn.dataTable.ext.pager.numbers_length = 4;
        objetoDataTables_Ministerios = $('#tableMinisterios').DataTable({
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
                "sLengthMenu": "Mostrar _MENU_ Ministerios",
                "sZeroRecords": "No se encontró ningun Ministerio con la Condición del Filtro",
                "sEmptyTable": "Ningun Ministerio Agregado aún...",
                "sInfo": "Del _START_ al _END_ de un total de _TOTAL_ Ministerio",
                "sInfoEmpty": "De 0 al 0 de un total de 0 Ministerio",
                "sInfoFiltered": "(filtrado de un total de _MAX_ Ministerios)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": loadingUI('Cargando listado de Ministerios...', 'white'),
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
                "url": "listar-ministerios",
                "data": {

                },
            },
            "initComplete": function(settings, json) {
                $.unblockUI();
                $('[data-toggle="popover"]').popover();
            },
            "columns": [{
                "className": 'celda_de_descripcion',
                "orderable": false,
                "data": null,
                "defaultContent": '<a class="botonesGraficos" href=""><i style="font-size: 20px;" class="fa fa-plus-circle text-success" aria-hidden="true"></i></a>'
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
                    className: "dt-head-center",
                    targets: [1, 2, 3, 4]
                },
                {
                    "width": "5%",
                    "targets": 0
                }, {
                    "width": "10%",
                    "targets": 1
                }, {
                    "width": "45%",
                    "targets": 2
                }, {
                    "width": "15%",
                    "targets": 3
                }, {
                    "width": "15%",
                    "targets": 4
                }, {
                    "width": "10%",
                    "targets": 4
                }
            ],

        });
        objetoDataTables_Ministerios.columns(5).visible(false);

    }

    function designColumn(table) {
        table.api().columns([2, 9]).every(function() {
            var column = this;
            if (column[0] == 2) {
                placeholder = 'Iglesia...';
            } else if (column[0] == 9) {
                placeholder = 'Status...';
            }


            var select = $('<select style="" id="' + idSelect + '" data-placeholder="' + placeholder + '" class="form-control chosen-select"><option value=""></option></select>')
                .appendTo($(column.header()).empty())
                .on('change', function() {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });
            idSelect = "";
            column.cells('', column[0]).render('display').sort().unique().each(function(d, j) {
                if (!firdColumn) {
                    dataFilter = d;
                    firdColumn = true;
                }
                select.append('<option value="' + d + '">' + d + '</option>')
            });
        });
    }


    $(document).on('click', '.botonesGraficos', function(event) {
        event.preventDefault();
    });

    $('#body-ministerios').on('click', 'td.celda_de_descripcion', function() {

        var filaDeLaTabla = $(this).closest('tr');
        var filaComplementaria = objetoDataTables_Ministerios.row(filaDeLaTabla);
        var celdaDeIcono = $(this).closest('td.celda_de_descripcion');

        if (filaComplementaria.child.isShown()) { // La fila complementaria está abierta y se cierra.
            filaComplementaria.child.hide();
            celdaDeIcono.html('<a style="font-size: 20px;"  class="botonesGraficos" href=""><i class="fa fa-plus-circle text-success" aria-hidden="true"></i></a>');
        } else { // La fila complementaria está cerrada y se abre.
            filaComplementaria.child(formatearSalidaDeDatosComplementarios(filaComplementaria.data(), 2)).show();
            celdaDeIcono.html('<a style="font-size: 20px;" class="botonesGraficos" href=""><i class="fa fa-minus-circle text-danger" aria-hidden="true"></i></a>');
        }

    });

    function formatearSalidaDeDatosComplementarios(filaDelDataSet, columna) {
        var cadenaDeRetorno = '';

        cadenaDeRetorno += '<div class="row" style="padding-top: 0;">';
        cadenaDeRetorno += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 0;">';
        cadenaDeRetorno += filaDelDataSet[4];
        cadenaDeRetorno += '</div>';

        return cadenaDeRetorno;
    }

    $(document).on('click', '.excluirMiembro', function(event) {
        event.preventDefault();
        idMiembro = $(this).attr('idMiembro');
        idMinisterio = $(this).attr('idMinisterio');

        alertify.confirm('Ministerios', '<h5 class="text-danger">Esta seguro de Excluir este Miembro ?</h5>', function() {
            $.ajax({
                url: 'excluir-miembro',
                type: 'get',
                data: {
                    idMiembro: idMiembro,
                    idMinisterio: idMinisterio
                },
                beforeSend: function() {
                    loadingUI('por favor espere, excluyendo al miembro del ministerio.');
                }
            }).done(function(data) {
                console.log(data)
                $.unblockUI();

                objetoDataTables_Ministerios.ajax.reload();
                $('[data-toggle="popover"]').popover();
                crearOrgranigrama();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });
        }, function() { // En caso de Cancelar              
            alertify.set('notifier', 'position', 'bottom-right');
            alertify.error('<i class="fa-2x fas fa-ban"></i><br>Se Cancelo el Proceso para Excluir al Miembro');
        }).set('labels', {
            ok: 'Confirmar',
            cancel: 'Cancelar'
        }).set({
            transition: 'zoom'
        }).set({
            modal: true,
            closableByDimmer: false
        });


    });


    $('body').on('click', '#body-ministerios a', function(e) {
        e.preventDefault();

        accion_ok = $(this).attr('data-accion');
        idMinisterio = $(this).attr('idMinisterio');
        Nombre = $(this).parents("tr").find("td")[2].innerHTML;

        switch (accion_ok) {

            case 'agregarMiembro': // Edita Usuario

                agregarMiembro(idMinisterio, Nombre);
                break;

            case 'editarMinisterio':
                $('#ModalMinisterio').modal('show');
                $("#tituloModalMinisterio").text('Actualizar datos del Ministerio.')
                $.ajax({
                    url: 'buscar-ministerio',
                    type: 'get',
                    datatype: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        idMinisterio: idMinisterio
                    }
                }).fail(function(statusCode, errorThrown) {
                    alert(statusCode + ' ' + errorThrown);
                }).done(function(response) {
                    console.log(response)

                    $("#idMinisterioAgregar").val(idMinisterio);
                    $("#nombreMinisterio").val(response.data.nombre);
                    $("#statusMinisterio").val(response.data.status);
                    $("#statusMinisterio").trigger("chosen:updated");

                    // status = data.status == 1 ? true : false;
                })
                $("#nombreMnisterio").focus();

                break;

            case 'bloquearMinisterio':

                $.ajax({
                    url: 'bloquear-ministerio',
                    type: 'get',
                    datatype: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        idMinisterio: idMinisterio
                    }
                }).fail(function(statusCode, errorThrown) {
                    console.log(statusCode + ' ' + errorThrown);
                }).done(function(response) {
                    console.log(response)
                    if (response.success === true) {
                        Pnotifica('Status.', response.mensaje, 'success', true);
                        objetoDataTables_Ministerios.ajax.reload();
                        crearOrgranigrama();
                    } else {
                        Pnotifica('Status.', response.mensaje, 'error', true);
                    }
                })
                break;


        }
    });

    function agregarMiembro(idMinisterio, Nombre) {
        $.ajax({
            url: 'agregar-miembro-ministerio',
            type: 'get',
            datatype: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                idMinisterio: idMinisterio
            }
        }).fail(function(statusCode, errorThrown) {
            alert(statusCode + ' ' + errorThrown);
        }).done(function(response) {
            console.log(response)

            $("#idMinisterio").val(idMinisterio);

            $('#tituloModal').text(Nombre);
            $('#fotoMiembro').html('<img src="images/user.png" alt="Foto Miembro" width="100px" height="125px" class="img-thumbnail center">');

            $('#ModalIncluirMiembroMinisterio').modal('show');

            $('#miembrosIncluir').empty();
            array = Object.values(response.data);
            $("#miembrosIncluir").append('<option></option>');
            $(array).each(function(i, v) {
                $("#miembrosIncluir").append('<option value="' + v.id + '">' + v.id + ' - ' + v.nombre + ' ' + v.apellido1 + ' ' + v.apellido2 + '</option>');
            })

            $("#miembrosIncluir").trigger("chosen:updated");

            // status = data.status == 1 ? true : false;
        })
    }

    $(document).on('change', '#miembrosIncluir', function(event) {
        idMiembro = $("#miembrosIncluir").val();

        $.ajax({
            url: 'buscar-foto-miembro',
            type: 'get',
            datatype: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                idMiembro: idMiembro
            }
        }).fail(function(statusCode, errorThrown) {
            alert(statusCode + ' ' + errorThrown);
        }).done(function(response) {
            console.log(response)

            $("#fotoMiembro").html(response.foto)


            // status = data.status == 1 ? true : false;
        })
    });


    $(document).on('click', '#btnIncluirMiembro', function(event) {
        event.preventDefault();
        idMiembro = $("#miembrosIncluir").val();
        nombreMiembro = $("#miembrosIncluir option:selected").text();

        idMinisterio = $("#idMinisterio").val();
        ministerio = $("#tituloModal").text();
        if (idMiembro == '') {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error('<i class="fa-2x fas fa-exclamation-triangle"></i><br>Debe seleccionar un Miembro para ser Incluido al Ministerio de <strong>' + ministerio + '</strong');
            return;
        }

        alertify.confirm('Ministerios', '<h5 class="text-danger">Esta seguro de Incluir este Miembro al <strong>Ministerio de ' + ministerio + '</strong> </h5>', function() {

            $.ajax({
                url: 'incluir-miembro-ministerio',
                type: 'get',
                data: {
                    idMiembro: idMiembro,
                    idMinisterio: idMinisterio
                },
                beforeSend: function() {
                    loadingUI('Incluyendo al Miembro <strong>' + nombreMiembro + '</strong> al Ministerio de <strong>' + ministerio + '</strong>');
                }
            }).done(function(data) {

                console.log(data)

                if (data.success === true) {
                    Pnotifica('Ministerios.', data.mensaje, 'success', true);
                    objetoDataTables_Ministerios.ajax.reload(null, true);
                } else {
                    Pnotifica('Ministerios.', data.mensaje, 'error', true);
                }
                crearOrgranigrama();
                $('#ModalIncluirMiembroMinisterio').modal('hide');

                $.unblockUI();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });

        }, function() { // En caso de Cancelar              
            alertify.set('notifier', 'position', 'bottom-right');
            alertify.error('<i class="fa-2x fas fa-ban"></i><br>Se Cancelo el Proceso para Incluir al Miembro');
        }).set('labels', {
            ok: 'Confirmar',
            cancel: 'Cancelar'
        }).set({
            transition: 'zoom'
        }).set({
            modal: true,
            closableByDimmer: false
        });

    });


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


    $("#FormMinisterio").validate({
        rules: {
            nombreMinisterio: "required"
        },
        messages: {
            nombreMinisterio: '<span  class="float-right"><i class="fas fa-exclamation-triangle"></i> Nombre del Ministerio requerido.</span>'
        },

        submitHandler: function(form) {

            alertify.confirm('Ministerio', '<h4 class="text-info">Esta seguro de guardar estos datos..?</h4>', function() {

                var form = $('#FormMinisterio');
                var formData = form.serialize()
                var route = form.attr('action');
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        loadingUI('Actualizando Ministerio');
                    }
                }).done(function(data) {
                    console.log(data)
                    $.unblockUI();
                    if (data.success === true) {
                        alertify.success(data.mensaje);
                    } else {
                        alertify.error(data.mensaje);
                    }
                    $('#FormMinisterio').each(function() {
                        this.reset();
                    });
                    objetoDataTables_Ministerios.ajax.reload();
                    crearOrgranigrama();
                    $('#ModalMinisterio').modal('hide');

                }).fail(function(statusCode, errorThrown) {
                    $.unblockUI();
                    console.log(errorThrown);
                    ajaxError(statusCode, errorThrown);
                });

            }, function() { // En caso de Cancelar              
                alertify.error('Se Cancelo el Proceso para Guardar los datos del Ministerio.');
            }).set('labels', {
                ok: 'Confirmar',
                cancel: 'Cancelar'
            }).set({
                transition: 'zoom'
            }).set({
                modal: true,
                closableByDimmer: false
            });



        }
    });


    $(document).on('click', '.btnAgregarMinisterio', function(event) {
        event.preventDefault();
        $('#FormMinisterio').each(function() {
            this.reset();
        });
        $('#ModalMinisterio').modal('show');
        $("#tituloModalMinisterio").text('Crear un nuevo Ministerio')
    });

    $(document).on('click', '.borrarMinisterio', function(event) {
        event.preventDefault();
        idMinisterio = $(this).attr('idMinisterio');
        Nombre = $(this).attr('Nombre');
        alertify.confirm('<i class="fa-2x text-danger far fa-trash-alt"></i> Ministerio', '<h5 class="float-center text-danger">Esta seguro de Borrar el Ministerio de <strong class="text-danger"> ' + Nombre + '</strong>  ?</h5>', function() {

            $.ajax({
                url: 'borrar-ministerio',
                type: 'get',
                data: {
                    idMinisterio: idMinisterio,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    loadingUI('Borrando Ministerio');
                }
            }).done(function(data) {
                console.log(data)
                $.unblockUI();
                if (data.success === true) {
                    alertify.success(data.mensaje);
                } else {
                    alertify.error(data.mensaje);
                }
                objetoDataTables_Ministerios.ajax.reload();
                crearOrgranigrama();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });

        }, function() { // En caso de Cancelar              
            alertify.error('Se Cancelo el Proceso para Borrar el Ministerio.');
        }).set('labels', {
            ok: 'Confirmar',
            cancel: 'Cancelar'
        }).set({
            transition: 'zoom'
        }).set({
            modal: true,
            closableByDimmer: false
        });
    });


    $(document).on('click', '#btnImprimirOrganigrama', function(event) {
        event.preventDefault();
        $('#organigrama').printThis({
            importCSS: true,
            //loadCSS: "path/to/new/CSS/file",
            header: '<h1 class="float-center">.:: Organigrama de los Ministerios ::.</h1>'
        });
    });

    $(document).on('click', '.agregarMiembro', function(event) {
        event.preventDefault();
        idMinisterio = $(this).attr('idMinisterio');
        Nombre = $(this).attr('Nombre');
        agregarMiembro(idMinisterio, Nombre)
    });


});
