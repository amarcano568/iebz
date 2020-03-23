$(document).on('ready', function() {

    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = '';
    var descripcionImg = '';
    var objetoDataTables_Empresas = '';

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";


    listarMaestroEmpresas();

    function listarMaestroEmpresas() {
        destroy_existing_data_table('#datatable-empresas');
        objetoDataTables_Empresas = $('#datatable-empresas').DataTable({
            responsive: true,
            "order": [
                [1, "asc"]
            ],
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "paginationType": "input",
            "sPaginationType": "full_numbers",
            "language": {
                "searchPlaceholder": "Buscar",
                "sProcessing": "Procesando...",
                "sLengthMenu": " _MENU_ Empresas",
                "sZeroRecords": "No se encontró ninguna Empresa con la Condición del Filtro",
                "sEmptyTable": "Ninguna Empresa Agregado aún...",
                "sInfo": "Del _START_ al _END_ de un total de _TOTAL_ Empresas",
                "sInfoEmpty": "De 0 al 0 de un total de 0 Empresas",
                "sInfoFiltered": "(filtrado de un total de _MAX_ Empresas)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": '<i class="text-info fa-2x fas fa-spinner fa-pulse"></i> espere cargando los Empresas...',
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
            "iDisplayLength": 5,
            "initComplete": function(settings, json) {
                $.unblockUI();
                $('[data-toggle="popover"]').popover();
            },
            "ajax": {
                "method": "get",
                "url": "carga-maestro-empresas",
                "data": {

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
            }, {
                "data": 7,
            }, {
                "data": 8,
            }],
            "columnDefs": [{
                "width": "5%",
                "targets": 0
            }, {
                "width": "20%",
                "targets": [1]
            }, {
                "width": "13%",
                "targets": 3
            }, {
                "width": "10%",
                "targets": 4
            }, {
                "width": "20%",
                "targets": [
                    5
                ]
            }, {
                "targets": [4],
                className: "text-center",
            }]
        });
    }


    $('#btnAgregarEmpresa').click(function() {
        $("#modal-maestro-empresa").modal('show');
        $("#tituloModal").text('Datos para crear la nueva Empresa');
        $('#form-maestro-empresa').each(function() {
            this.reset();
        });
        $("#BtnSaveActualizarEmpresa").hide();
        $("#BtnSaveNewEmpresa").show();
    });

    $('.chosen-select', this).chosen('destroy').chosen({
        width: '100%',
        height: '200%',
        disable_search_threshold: 10,
        no_results_text: "Oops, busqueda no encontrada!"
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

    $("#form-maestro-empresa").validate({
        rules: {
            nombreEmpresa: "required",
            nroRuc: "required",
            baseDatos: "required",
            usuariosPermitidos: "required",
            emailEmpresa: "required",
            representante: "required",
            direccion: "required",
            nombresUsuario: "required",
            apellidosUsuario: "required",
            correoUsuario: "required"
        },
        messages: {
            nombreEmpresa: "",
            nroRuc: "",
            baseDatos: "",
            usuariosPermitidos: "",
            emailEmpresa: "",
            representante: "",
            direccion: "",
            nombresUsuario: "<i class='fas fa-exclamation-triangle'></i> Nombre usuario requerido.",
            apellidosUsuario: "<i class='fas fa-exclamation-triangle'></i> Apellido requerido.",
            correoUsuario: "<i class='fas fa-exclamation-triangle'></i> Email requerido."
        },

        submitHandler: function(form) {

            alertify.confirm('Crear Empresa', '<h4 class="text-info">Esta seguro de crear esta nueva Empresa..?</h4>', function() {

                var form = $('#form-maestro-empresa');
                var formData = form.serialize();
                var route = form.attr('action');
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        loadingUI('por favor espere, se esta creando la nueva empresa.');
                    }
                }).done(function(data) {
                    console.log(data)
                    $.unblockUI();
                    if (data.success === true) {
                        alertify.success('Empresa Creada satisfactoriamente....');
                        objetoDataTables_Empresas.ajax.reload();
                    } else {
                        Pnotifica('Configuración previa..', data.mensaje, 'error', false);
                    }
                    $('#form-maestro-empresa').each(function() {
                        this.reset();
                    });

                    $('#modal-maestro-empresa').modal('hide');

                }).fail(function(statusCode, errorThrown) {
                    $.unblockUI();
                    console.log(errorThrown);
                    ajaxError(statusCode, errorThrown);
                });

            }, function() { // En caso de Cancelar              
                alertify.error('Se Cancelo el Proceso para crear la nueva empresa.');
            }).set('labels', {
                ok: 'Crear',
                cancel: 'Cancelar'
            }).set({
                transition: 'zoom'
            }).set({
                modal: true,
                closableByDimmer: false
            });



        }
    });

    $('body').on('click', '#body-empresas a', function(e) {
        e.preventDefault();

        accion_ok = $(this).attr('data-accion');
        idEmpresa = $(this).attr('idempresa');

        if (accion_ok == "editarEmpresa") { // Editar Empresa

            $("#tituloModal").text('Actualización de datos para la Empresa');
            $('#form-maestro-empresa').each(function() {
                this.reset();
            });
            $("#BtnSaveNewEmpresa").hide();
            $("#BtnSaveActualizarEmpresa").show();

            $('#baseDatos').prop('readonly', true);
            $('#emailEmpresa').prop('readonly', true);
            $('#correoUsuario').prop('readonly', true);

            obtenerEmpresa(idEmpresa);

        } else if (accion_ok == 'bloquearEmpresa') {
            alertify.confirm('Cambiar de Status de Empresa', '<h5 class="text-danger">Esta seguro de hacer este cambio <i class="fa-2x fa fa-question-circle-o" aria-hidden="true"></i></h5>', function() {

                $.ajax({
                    url: 'Act-Des-Empresa',
                    type: 'get',
                    data: {
                        "idEmpresa": idEmpresa
                    },
                    beforeSend: function() {
                        loadingUI('Actualizando');
                    }
                }).done(function(data) {

                    console.log(data)

                    if (data.success === true) {
                        Pnotifica('Empresa.', data.mensaje, 'success', true);
                        objetoDataTables_Empresas.ajax.reload(null, false);
                    } else {
                        Pnotifica('Empresa.', data.mensaje, 'error', true);
                    }

                    $.unblockUI();

                }).fail(function(statusCode, errorThrown) {
                    $.unblockUI();
                    console.log(errorThrown);
                    ajaxError(statusCode, errorThrown);
                });

            }, function() { // En caso de Cancelar              
                alertify.error('Se Cancelo el Proceso para cambiar de status la Empresa');
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

    function obtenerEmpresa(idEmpresa) {

        $.ajax({
            url: 'obtener-informacion-empresa',
            type: 'get',
            data: {
                "idEmpresa": idEmpresa
            },
            beforeSend: function() {
                loadingUI('Obteniendo información de la Empresa.');
            }
        }).done(function(data) {

            console.log(data)
            $("#idEmpresa").val(data.data.id_Empresa);
            $("#nombreEmpresa").val(data.data.NombreEmpresa);
            $("#nroRuc").val(data.data.ruc);
            $("#baseDatos").val(data.data.nameBd);
            $("#usuariosPermitidos").val(data.data.usuariosPermitidos);
            $("#telFijo").val(data.data.telefono1);
            $("#telMovil").val(data.data.telefono2);
            $("#emailEmpresa").val(data.data.correo);
            $("#representante").val(data.data.representante);
            $("#direccion").val(data.data.direccion);
            $("#idUsuario").val(data.data.userAdmin);
            $("#nombresUsuario").val(data.data.name);
            $("#apellidosUsuario").val(data.data.lastName);
            $("#correoUsuario").val(data.data.email);
            $("#telefonoUsuario").val(data.data.Telefono);
            $("#modal-maestro-empresa").modal('show');
            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
    }

    $('#BtnSaveActualizarEmpresa').click(function() {
        if ($('#form-maestro-empresa').valid()) {
            alertify.confirm('Actualizar información de la Empresa', '<h4 class="text-info">Esta seguro de actualizar la información de la Empresa..?</h4>', function() {
                var form = $('#form-maestro-empresa');
                var formData = form.serialize();
                var route = form.attr('action');
                $.ajax({
                    url: 'actualizar-datos-empresa',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        loadingUI('por favor espere, se esta actualizando los datos de la empresa.');
                    }
                }).done(function(data) {
                    console.log(data)
                    $.unblockUI();
                    if (data.success === true) {
                        alertify.success('Datos de la Empresa actualizados satisfactoriamente....');
                        objetoDataTables_Empresas.ajax.reload();
                    } else {
                        Pnotifica('Configuración previa..', data.mensaje, 'error', false);
                    }
                    $('#form-maestro-empresa').each(function() {
                        this.reset();
                    });

                    $('#modal-maestro-empresa').modal('hide');

                }).fail(function(statusCode, errorThrown) {
                    $.unblockUI();
                    console.log(errorThrown);
                    ajaxError(statusCode, errorThrown);
                });
            }, function() { // En caso de Cancelar              
                alertify.error('Se Cancelo el Proceso para actualizar los datos de la empresa.');
            }).set('labels', {
                ok: 'Actualizar',
                cancel: 'Cancelar'
            }).set({
                transition: 'zoom'
            }).set({
                modal: true,
                closableByDimmer: false
            });

        }

    });


});