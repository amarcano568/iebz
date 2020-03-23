$(document).on('ready', function() {

    $('.chosen-select').chosen(ConfigChosen());
    var objetoDataTables_personal = '';
    var minAdjuntas = '';
    var minAdjuntasResultados = '';

    CargaTablero();

    function CargaTablero() {
        $.ajax({
            url: 'listar_resumen_ticket_usuarios',
            type: 'get',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
            },
            beforeSend: function() {
                loadingUI('Actualizando');
            }
        }).done(function(response) {

            console.log(response)

            obj = response.data;
            $.each(obj, function(key, value) {
                if (value.grupo == 1) { // Abiertos
                    $("#TicketsAbiertos").text(value.cantidad);
                } else if (value.grupo == 2) { // En Procesos
                    $("#TicketsProcesos").text(value.cantidad);
                } else if (value.grupo == 3) { // Pausados
                    $("#TicketsPausados").text(value.cantidad);
                } else if (value.grupo == 4) { // Terminados
                    $("#TicketsTerminados").text(value.cantidad);
                } else if (value.grupo == 5) { // Total Enviados
                    $("#TicketsTodos").text(value.cantidad);
                } else if (value.grupo == 6) { // Total Anulados
                    $("#TicketsAnulados").text(value.cantidad);
                }
            });

            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });

    }

    var Area = $("#Area").val();
    if (Area != undefined) {
        FuncSelectTipoTicket(Area);
    }

    $(document).on('change', '#Area', function(event) {
        var Area = $("#Area").val();
        var TipoTicket = $("#TipoTicket").val();
        $(".help-block").remove();
        if (Area != '') {

            FuncSelectTipoTicket(Area)
        }

    });

    function FuncSelectTipoTicket(IdArea) {
        $.ajax({
            url: 'listar-tipo_ticket',
            type: 'get',
            dataType: 'json',
            data: {
                idArea: IdArea,
                _token: "{{ csrf_token() }}",
            },
            beforeSend: function() {
                loadingUI('Actualizando');
            }
        }).done(function(data) {

            console.log(data.data)
            $("#TipoTicket").empty();
            arrayTipoTickets = data.data;
            $(arrayTipoTickets).each(function(i, data1) {
                $("#TipoTicket").append('<option value="' + data1.idTabla + '">' + data1.desTabla + '</option>');
            });
            $("#TipoTicket").trigger("chosen:updated");
            var TipoTicket = $("#TipoTicket").val();
            FuncSelectCategoria(IdArea, TipoTicket);

            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
    }

    $(document).on('change', '#TipoTicket', function(event) {
        var Area = $("#Area").val();
        var TipoTicket = $("#TipoTicket").val();
        $(".help-block").remove();
        if (Area !== null && TipoTicket !== null) {
            FuncSelectCategoria(Area, TipoTicket);
        }

    });

    function FuncSelectCategoria(IdArea, tipoTicket) {
        if (tipoTicket === null || tipoTicket === undefined || tipoTicket == '') {
            return false;
        }
        $.ajax({
            url: 'listar-categoria-ticket',
            type: 'get',
            dataType: 'json',
            data: {
                idArea: IdArea,
                tipoTicket: tipoTicket,
                _token: "{{ csrf_token() }}",
            },
            beforeSend: function() {
                //loadingUI('Actualizando');
            }
        }).done(function(data) {

            console.log(data.data)
            $("#CategoriaX").empty();
            arrayTipoTickets = data.data;
            $(arrayTipoTickets).each(function(i, data1) {
                $("#CategoriaX").append('<option value="' + data1.idCategoria + '">' + data1.descCategoria + '</option>');
            });
            $("#CategoriaX").trigger("chosen:updated");
            var IdCat = $("#CategoriaX").val();
            if (IdCat !== null) {
                FuncSelectSubCategoria(IdCat);
            }

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });

    }

    function FuncSelectSubCategoria(IdCat) {
        $.ajax({
            url: 'carga-sub-categoria',
            type: 'get',
            dataType: 'json',
            data: {
                IdCat: IdCat,
                _token: "{{ csrf_token() }}",
            },
            beforeSend: function() {
                //loadingUI('Actualizando');
            }
        }).done(function(data) {

            console.log(data.data)
            $("#SubCategoria").empty();
            arrayTipoTickets = data.data;
            $(arrayTipoTickets).each(function(i, data1) {
                $("#SubCategoria").append('<option value="' + data1.idSubCategoria + '">' + data1.desSubCategoria + '</option>');
            });
            $("#SubCategoria").trigger("chosen:updated");
            IdSubCat = $('#SubCategoria').val();
            FuncSelectProblemasFrecuentes(IdSubCat);

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });

    }

    $(document).on('change', '#CategoriaX', function(event) {
        var IdCat = $("#CategoriaX").val();
        $(".help-block").remove();
        if (IdCat === null) {
            $("#SubCategoriaNueva").html('La Categoría seleccionada no tiene SubCategoría definida.');
        } else {
            FuncSelectSubCategoria(IdCat);
        }
    });

    $(document).on('change', '#SubCategoria', function(event) {
        IdSubCat = $('#SubCategoria').val();
        console.log(IdSubCat)
        FuncSelectProblemasFrecuentes(IdSubCat);
    });

    function FuncSelectProblemasFrecuentes(IdSubCat) {

        $.ajax({
            url: 'carga-sub-problemas',
            type: 'get',
            dataType: 'json',
            data: {
                IdSubCat: IdSubCat,
                _token: "{{ csrf_token() }}",
            },
            beforeSend: function() {
                //loadingUI('Actualizando');
            }
        }).done(function(data) {
            console.log(data.data)
            $("#Problemas").empty();
            arrayTipoTickets = data.data;
            $(arrayTipoTickets).each(function(i, data1) {
                $("#Problemas").append('<option value="' + data1.idSoluFrecuente + '">' + data1.descripcion + '</option>');
            });
            $("#Problemas").trigger("chosen:updated");

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
    }

    configuraDropZone();

    function configuraDropZone() {
        iconoDropZone = '<i class="text-primary far fa-hand-point-up"></i>';
        Dropzone.autoDiscover = false;
        if (Dropzone.instances.length > 0) Dropzone.instances.forEach(bz => bz.destroy());
        $("#formDropZone").html('');
        $("#formDropZone").append("<form action='up-files-support' method='POST' files='true' enctype='multipart/form-data' id='dZUpload' class='dropzone borde-dropzone' style='width: 100%;padding: 0;cursor: pointer;'>" +
            "<div style='padding: 0;margin-top: 0em;' class='dz-default dz-message text-center'>" +
            "<h1>" + iconoDropZone + "</h1><p class='text-danger'>Arrastre varios archivos para cargar múltiples o haga clic para seleccionar archivos..</p>" +
            "</div></form>");

        myAwesomeDropzone = myAwesomeDropzone = {
            maxFilesize: 12,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                return time + file.name;
            },
            acceptedFiles: 'image/* ,.jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF,.xls,.xlsx,.doc,.docx,.txt,.pdf,.msg,.XLS,.XLSX,.DOC,.DOCX,.TXT,.PDF,.zip,.rar,.ZIP,.RAR,.MSG,capture=camera',
            addRemoveLinks: true,
            timeout: 50000,
            removedfile: function(file) {
                var name = file.upload.filename;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("delete") }}',
                    data: {
                        filename: name
                    },
                    success: function(data) {
                        console.log("File has been successfully removed!!");
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
                var fileRef;
                return (fileRef = file.previewElement) != null ?
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },
            params: {
                idSucursal: '',
            },
            success: function(file, response) {
                console.log(response);
                fotoSubida = response
            },
            error: function(file, response) {
                return false;
            }
        }

        var myDropzone = new Dropzone("#dZUpload", myAwesomeDropzone);

        myDropzone.on("queuecomplete", function(file, response) {

            // if (Dropzone.instances.length > 0) Dropzone.instances.forEach(bz => bz.destroy());


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
    $("#FormCanalTicket").validate({
        rules: {
            Area: {
                required: true
            },
            TipoTicket: {
                required: true
            },
            CategoriaX: {
                required: true
            }
        },
        messages: {
            Area: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Debe introducir Area del ticket.',
            TipoTicket: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Debe seleccionar el Tipo.',
            CategoriaX: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Debe seleccionar la Categoría del ticket.'
        },
        submitHandler: function(form) {
            return true;
        }
    })

    $("#FormDescripcionProblema").validate({
        rules: {
            Titulo: {
                required: true
            }
        },
        messages: {
            Titulo: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Debe introducir el Titulo de la solicitud de soporte.'
        },
        submitHandler: function(form) {
            return true;
        }
    })

    $(document).on('click', '.botonResumen', function(event) {
        event.preventDefault();
        $("#PantallaPrincipal").hide();
        $("#listadoTickets").show();
        titulo = $(this).attr('titulo');
        EstadoTickes = $(this).attr('EstadoTickes');
        $("#titleListadoTickets").text(titulo);

        muestraDataTables(EstadoTickes);

    });

    $(document).on('click', '#btnRegresar', function(event) {
        event.preventDefault();

        $("#PantallaPrincipal").show();
        $("#listadoTickets").hide();

    });

    function muestraDataTables(EstadoTickes) {

        destroy_existing_data_table('#TableTickets');
        $.fn.dataTable.ext.pager.numbers_length = 4;
        objetoDataTables_personal = $('#TableTickets').DataTable({
            responsive: true,
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
                "url": "listar-tickets-usuarios",
                "data": {
                    EstadoTickes: EstadoTickes
                }
            },
            "initComplete": function(settings, json) {
                $.unblockUI();
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
            }, {
                "data": 7,
            }, {
                "data": 8,
                "className": 'dt-center'
            }, {
                "data": 9,
            }],
            "columnDefs": [{
                "width": "5%",
                "targets": [0, 1]
            }, {
                "width": "20%",
                "targets": [
                    2, 3, 4
                ]
            }, {
                "width": "5%",
                "targets": [
                    8
                ]
            }, {
                "targets": [4],
                className: "text-center",
            }]


        });


    }

    /*
      Eventos Click sobre la Table <Tciket> para ver detalles.
     */
    $('body').on('click', '#listaListadoTicketDetalleOK a', function(e) {
        e.preventDefault();

        accion_ok = $(this).attr('data-accion');

        nroTicket = $(this).attr('nroTicket');
        estadoTickecAnt = $(this).attr('estado');
        $("#IdEstadoAnterior").val(estadoTickecAnt);
        if (accion_ok == "DetalleTicket") { // Ver Editar Categoria 
            buscarDetalleTicket(nroTicket, estadoTickecAnt);

        }

    });

    function buscarDetalleTicket(nroTicket, estadoTickecAnt) {
        $("#ModalDetalleTicket").modal('show');
        $("#TituloNroTicket").html(nroTicket);
        $.ajax({
            url: 'obtener-detalle-ticket',
            type: 'get',
            dataType: 'json',
            data: {
                nroTicket: nroTicket,
                estadoTickecAnt: estadoTickecAnt,
                rol: 'USU',
                _token: "{{ csrf_token() }}",
            },
            beforeSend: function() {
                loadingUI('Obteniendo detalle del Ticket..!');
            }
        }).done(function(response) {
            console.log(response.data)


            obj = response.data;
            $.each(obj, function(key, value) {
                desTabla = value.desTabla
                desPrioridad = value.desPrioridad
                desEstado = value.desEstado
                fechaEstado = value.fechaEstado
                descCategoria = value.descCategoria
                desSubCategoria = value.desSubCategoria
                descArea = value.descArea
                titulo = value.titulo
                descripcion = value.descripcion
                nomUsuTicket = value.nomUsuTicket
                fechaRegistro = value.fechaRegistro
                idNivelEncuesta = value.idNivelEncuesta
            })

            $("#tipoTicket").text(desTabla);
            $("#prioridadTicket").text(desPrioridad);
            $("#estadoTicket").text(desEstado + ' ' + fechaEstado);
            $("#categoriaTicket").val(descCategoria);
            $("#subCategoriaTicket").val(desSubCategoria);
            $("#areaTicket").val(descArea);
            $("#tituloTicket").val(titulo);
            $("#descripcionSoporte").html(descripcion);
            $("#creadoPor").html('<i class="far fa-user"></i> ' + nomUsuTicket);
            $("#fechaRegistro").html('<i class="far fa-calendar-alt"></i> ' + fechaRegistro);

            $("#totalAdjuntos").text(response.totalAdjuntos);
            $("#totalAdjuntosResultado").text(response.totalAdjuntosResultados);

            minAdjuntas = response.miniaturasAdjuntas;
            minAdjuntasResultados = response.minAdjResultados;

            obj = response.accionesTicket;

            botones = '';


            $.each(obj, function(key, value) {

                botones += '<button id="BtnEventoTicket' + value.idTabla + '" type="button" class="btn btn-round btn-info">' + value.icono + ' ' + value.desTabla + '</button>';
            });
            $("#botonera").html(botones);

            $("#eventosTicket").html(response.eventosTickets);
            $('article').readmore({
                speed: 500,
                collapsedHeight: 50,
                moreLink: '<a href="#"><span class="text-primary">Leer más</span></a>',
                lessLink: '<a href="#"><span class="text-primary">Leer menos</span></a>'
            });
            $('[data-toggle="tooltip"]').tooltip();
            $.unblockUI();
        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
    }

    $('#ModalDetalleTicket').on('show.bs.modal', function(event) {
        $("#eventosTicket").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> Obteniendo Eventos del Ticket.');
    })

    /* Anular Ticket*/
    $(document).on('click', '#BtnEventoTicket3', function(event) {
        $("#ModalAnularTicket").modal('show');
    });

    $("#btnFilesAjuntos").click(function(event) {
        var tot = $("#totalAdjuntos").text();
        if (tot == 0) {
            Pnotifica('Archivos adjuntos.', 'Este ticket no contiene archivos adjuntos.', 'warning', true);
            return false;
        }
        $("#miniaturas").html(minAdjuntas);
        $("#ModalArchivosAdjuntos").modal('show');
    });

    $("#btnFilesAjuntosResultados").click(function(event) {
        var tot = $("#totalAdjuntosResultado").text();
        if (tot == 0) {
            Pnotifica('Archivos adjuntos resultados.', 'Este ticket no contiene archivos adjuntos.', 'warning', true);
            return false;
        }
        $("#miniaturas").html(minAdjuntasResultados);
        $("#ModalArchivosAdjuntos").modal('show');
    });

    $("#BtnAnularTicket").click(function(event) {
        event.preventDefault();
        var estadoTickecAnt = $("#IdEstadoAnterior").val();
        var nroTicket = $("#TituloNroTicket").text();
        var DescripcionAnular = $("#DescripcionAnular").val();

        if (DescripcionAnular == "") {
            Pnotifica('Anular Ticket.', 'Debe explicar el motivo de Anulación del ticket', 'error', true);
            $("#DescripcionAnular").focus();
            return false;
        }
        alertify.confirm('Anular Ticket', 'Esta Seguro de Anular este Ticket', function() {

            $.ajax({
                url: 'anular-ticket',
                type: 'get',
                dataType: 'json',
                data: {
                    nroTicket: nroTicket,
                    estadoTickecAnt: estadoTickecAnt,
                    DescripcionAnular: DescripcionAnular,
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    loadingUI('Anulando Ticket..!');
                }
            }).done(function(response) {
                console.log(response.data)

                objetoDataTables_personal.ajax.reload(null, false);
                if (response.success === true) {
                    Pnotifica('Ticket Anulado', response.mensaje, 'success', true);
                } else {
                    Pnotifica('Tipo Anulado', response.mensaje, 'error', true);
                }

                $("#ModalAnularTicket").modal('hide');
                $("#ModalDetalleTicket").modal('hide');
                CargaTablero();
                $.unblockUI();
            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });

        }, function() { // En caso de Cancelar              
            alertify.error('Se Cancelo el Proceso de Anular este Ticket.')
        }).set('labels', {
            ok: 'Confirmar',
            cancel: 'Cancelar'
        }).set({
            transition: 'zoom'
        });

    });

    $(document).on('click', '.clickPicture', function(event) {
        fileImage = $(this).attr('nameFile');
        nameShort = $(this).attr('nameShort');
        nameDate = $(this).attr('nameDate');

        $("#nameFileDetalle").html('<h6><i class="fas fa-image"></i> ' + nameShort + '</h6>');
        $("#dateFileDetalle").html('<h6><i class="far fa-calendar-alt"></i> ' + nameDate + '</h6>');

        $("#btnDescargarImagen").attr('href', fileImage);
        $("#btnDescargarImagen").attr('download', nameShort);

        $("#ModalDetalleImagen").modal('show');
        imagen = '<img id="imgZoom" data-zoom-image="' + fileImage + '" style="height: 23em;display: block;width: 100%" src="' + fileImage + '" alt="image" />'
        $("#divDetalleImagen").html(imagen);
        $('#imgZoom').elevateZoom({
            zoomType: "lens",
            lensShape: "round",
            lensSize: 200
        });
    });

    $(document).on('click', '#BtnEventoTicket10', function(event) {
        $("#ModalEncuestaTicket").modal('show');
        $("#estrellas").rating({
            'size': 'lg'
        });
    });


    $(document).on('click', '#btnEnviarEncuesta', function(event) {
        event.preventDefault();

        var descEncuesta = $("#DescripcionEncuesta").val();
        if (descEncuesta == '') {
            Pnotifica('Encuesta del ticket.', 'Por favor escriba un Comentario del servicio recibido.', 'error', true);
            $("#DescripcionEncuesta").focus();
            return false;
        }

        alertify.confirm('Encuesta del Ticket', '<h5 class="blue">Esta Seguro de Enviar esta Evaluación?', function() {
            var nroTicket = $("#TituloNroTicket").text();
            var estrellas = $("#estrellas").val();
            var DescripcionEncuesta = $("#DescripcionEncuesta").val();
            $.ajax({
                url: 'encuesta-ticket',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    nroTicket: nroTicket,
                    estrellas: estrellas,
                    DescripcionEncuesta: DescripcionEncuesta
                },
                beforeSend: function() {
                    loadingUI('Anulando Ticket..!');
                }
            }).done(function(response) {
                console.log(response.data)

                objetoDataTables_personal.ajax.reload(null, false);
                if (response.success === true) {
                    Pnotifica('Encuesta ticket', response.mensaje, 'success', true);
                } else {
                    Pnotifica('Encuesta ticket', response.mensaje, 'error', true);
                }

                $("#ModalEncuestaTicket").modal('hide');
                $("#ModalDetalleTicket").modal('hide');
                CargaTablero();
                $.unblockUI();
            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });

            $("#ModalEncuestaTicket").modal('hide');
            $('#FormEncuestaTicket').each(function() {
                this.reset();
            });


        }, function() { // En caso de Cancelar              
            alertify.error('Se Cancelo el Proceso de enviar la encuesta.')
        }).set('labels', {
            ok: 'Confirmar',
            cancel: 'Cancelar'
        }).set({
            transition: 'zoom'
        });

    });

});