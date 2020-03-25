$(document).on('ready', function() {

    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = '';
    var descripcionImg = '';
    var objetoDataTables_Usuarios = '';

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";

    listarUsuarios();

    function listarUsuarios() {
        destroy_existing_data_table('#datatable-usuarios');
        objetoDataTables_Usuarios = $('#datatable-usuarios').DataTable({
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
                "sLengthMenu": " _MENU_ Usuarios",
                "sZeroRecords": "No se encontró ninguna Usuario con la Condición del Filtro",
                "sEmptyTable": "Ninguna Usuario Agregado aún...",
                "sInfo": "Del _START_ al _END_ de un total de _TOTAL_ Usuarios",
                "sInfoEmpty": "De 0 al 0 de un total de 0 Usuarios",
                "sInfoFiltered": "(filtrado de un total de _MAX_ Usuarios)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": '<i class="text-info fa-2x fas fa-spinner fa-pulse"></i> espere cargando los Usuarios...',
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
                "url": "carga-Usuarios",
                "data": {

                }
            },
            "columns": [{
                "data": 0
            }, {
                "data": 1,
            }, {
                "data": 2
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
                "width": "3%",
                "targets": [0, 5]
            }, {
                "width": "30%",
                "targets": [3]
            }, {
                "width": "10%",
                "targets": [4]
            }, {
                "width": "15%",
                "targets": [
                    1,
                    2
                ]
            }, {
                "targets": [4],
                className: "text-center",
            }]
        });
    }

    $('#btnAgregarUsuario').click(function() {
        validaAgregarUsuario();
    });

    function validaAgregarUsuario() {

        $('#form_register_usuario').each(function() {
            this.reset();
        });
        $("#cargo").val('').trigger("chosen:updated");
        $("#area").val('').trigger("chosen:updated");
        $("#subArea").val('').trigger("chosen:updated");
        $('#modal-usuario').modal('show');
        $('#exampleModalLabel').html('<i class="text-primary fas fa-user-plus"></i> Agregar un nuevo usuario..!')
    }

    $('#modal-usuario').on('shown.bs.modal', function() {
        //$('#myInput').trigger('focus')
        var pArea = $('#area').val();
        listar_sub_areas(pArea);

    })

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

    $("#form_register_usuario").validate({
        rules: {
            nombres: "required",
            apellidos: "required",
            correo: "required",
            telefono: "required",
            area: "required",
            subArea: "required",
            rol: "required",
            status: "required"
        },
        messages: {
            nombres: "<i class='fas fa-exclamation-triangle'></i> Nombres requerido.",
            apellidos: "<i class='fas fa-exclamation-triangle'></i> Apellidos requerido.",
            correo: "<i class='fas fa-exclamation-triangle'></i> Correo requerido.",
            telefono: "<i class='fas fa-exclamation-triangle'></i> Teléfono requerido.",
            area: "<i class='fas fa-exclamation-triangle'></i> Área requerida.",
            subArea: "<i class='fas fa-exclamation-triangle'></i> Sub área requerida.",
            rol: "<i class='fas fa-exclamation-triangle'></i> Rol requerido.",
            status: "<i class='fas fa-exclamation-triangle'></i> Status requerido."
        },

        submitHandler: function(form) {

            alertify.confirm('Usuario', '<h4 class="text-info">Esta seguro de guardar estos datos..?</h4>', function() {

                if ($('#status').prop('checked')) {
                    estado1 = 1;
                } else {
                    estado1 = 0;
                }


                var form = $('#form_register_usuario');
                var formData = form.serialize() + "&estado=" + estado1;
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
                    $.unblockUI();
                    if (data.success === true) {
                        alertify.success('Datos del Usuario actualizado....');
                    } else {
                        alertify.success('Error - no se pudo actualizar los datos del Usuario...');
                    }
                    $('#form_register_usuario').each(function() {
                        this.reset();
                    });
                    objetoDataTables_Usuarios.ajax.reload();
                    $('#modal-usuario').modal('hide');

                }).fail(function(statusCode, errorThrown) {
                    $.unblockUI();
                    console.log(errorThrown);
                    ajaxError(statusCode, errorThrown);
                });

            }, function() { // En caso de Cancelar              
                alertify.error('Se Cancelo el Proceso para Guardar los datos del Usuario.');
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

    $('body').on('click', '#body-usuarios a', function(e) {
        e.preventDefault();

        accion_ok = $(this).attr('data-accion');
        idUsuario = $(this).attr('idUsuario');
        url_rol = $(this).attr('urlRole');
        switch (accion_ok) {

            case 'editarUsuario': // Edita Usuario

                $.ajax({
                    url: 'buscar_usuario',
                    type: 'get',
                    datatype: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        idUsuario: idUsuario
                    }
                }).fail(function(statusCode, errorThrown) {
                    alert(statusCode + ' ' + errorThrown);
                }).done(function(response) {
                    console.log(response)
                    $('#modal-usuario').modal('show');
                    $('#exampleModalLabel').html('<i class="text-primary fas fa-user-plus"></i> Editar información del usuario..!')

                    $("#idUsuario").val(response.data.id);
                    $("#nombres").val(response.data.name);
                    $("#apellidos").val(response.data.lastName);
                    $("#correo").val(response.data.email);
                    $("#telefono").val(response.data.Telefono);
                    $("#cargo").val(response.data.cargo).trigger("chosen:updated");
                    $("#area").val(response.data.idArea).trigger("chosen:updated");
                    $("#subArea").val(response.data.idSubArea).trigger("chosen:updated");
                    var element = $('#status');
                    if (response.data.status == 1) {
                        changeSwitchery(element, true);
                    } else {
                        changeSwitchery(element, false);
                    }

                    $("#rol").val(response.data.rol).trigger("chosen:updated");;

                    // status = data.status == 1 ? true : false;
                })
                break;

            case 'editarRole': // Edita Usuario
                window.location.href = url_rol;

                break;

            case 'bloquearUsuario':

                $.ajax({
                    url: 'bloquear_usuario',
                    type: 'get',
                    datatype: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        idUsuario: idUsuario
                    }
                }).fail(function(statusCode, errorThrown) {
                    console.log(statusCode + ' ' + errorThrown);
                }).done(function(response) {
                    console.log(response)
                    if (response.success === true) {
                        Pnotifica('Status.', response.mensaje, 'success', true);
                        objetoDataTables_Usuarios.ajax.reload();
                    } else {
                        Pnotifica('Status.', response.mensaje, 'error', true);
                    }
                })
                break;

            case 'interactuaCayro':

                $.ajax({
                    url: 'interactua-cayro',
                    type: 'get',
                    datatype: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        idUsuario: idUsuario
                    }
                }).fail(function(statusCode, errorThrown) {
                    console.log(statusCode + ' ' + errorThrown);
                }).done(function(response) {
                    console.log(response)
                    if (response.success === true) {
                        Pnotifica('Interactua Cayro.', response.mensaje, 'success', true);
                        objetoDataTables_Usuarios.ajax.reload();
                    } else {
                        Pnotifica('Interactua Cayro.', response.mensaje, 'error', true);
                    }
                })
                break;


        }
    });

    //me listara las sub_areas dependiendo del area elegida es para el modulo de usuarios

    $("#area").on('change', function() {

        iArea = $(this).val();

        listar_sub_areas(iArea);

    });


    function listar_sub_areas(iArea) {

        $.ajax({
            url: 'get-sub-area-usuarios',
            type: 'get',
            datatype: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                iArea: iArea
            }
        }).fail(function(statusCode, errorThrown) {
            console.log(statusCode + ' ' + errorThrown);
        }).done(function(response) {


            array = Object.values(response.data);

            if (response.success == true) {

                $('#subArea').empty();

                $(array).each(function(i, v) {
                    $("#subArea").append('<option value="' + v.idSubArea + '">' + v.descSubArea + '</option>');
                })

                $("#subArea").trigger("chosen:updated");
            } else {
                console.log("hubo un error");

            }
        })

    }

});
