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
                "url": "listar-miembros",
                "data": {

                },
            },
            "initComplete": function(settings, json) {
                $.unblockUI();
                firdColumn = false;
                idSelect = "idAreaColumn";
                console.log(this)
                designCol = this;
                designColumn(designCol);
                //objetoDataTables_Miembros.search(dataFilter).draw();
                $(".chosen-select").chosen(configChosen());
                $('#idAreaColumn').trigger("chosen:updated");
                $('[data-toggle="popover"]').popover()

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
            }, {
                "data": 6
            }, {
                "data": 7
            }, {
                "data": 8
            }, {
                "data": 9
            }, {
                "data": 10
            }],
            "columnDefs": [{
                    className: "dt-head-center",
                    targets: [3, 4, 5, 6, 7, 8]
                },
                {
                    "width": "2.5%",
                    "targets": 0
                }, {
                    "width": "5%",
                    "targets": 1
                }, {
                    "width": "5%",
                    "targets": 2
                }, {
                    "width": "30%",
                    "targets": 3
                }, {
                    "width": "30%",
                    "targets": 4
                }, {
                    "width": "30%",
                    "targets": 5,
                    "className": "text-center"
                }, {
                    "width": "5%",
                    "targets": 6
                }, {
                    "width": "5%",
                    "targets": 7
                }, {
                    "width": "5%",
                    "targets": 8
                }, {
                    "width": "5%",
                    "targets": 9,
                    "orderable": false
                }, {
                    "width": "5%",
                    "targets": 10
                }, {
                    "width": "5%",
                    "targets": 11,
                    "orderable": false
                }
            ],

        });
        objetoDataTables_Miembros.columns(10).visible(false);

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

    $(document).on('change', '#idAreaColumn', function(event) {
        objetoDataTables_Miembros.search('').draw();
    });

    function configChosen() {

        return ({
            no_results_text: 'No hay resultados para ',
            placeholder_text_single: 'Seleccione una Opción',
            disable_search_threshold: 10,
            allow_single_deselect: true

        })
    }

    $(document).on('click', '.botonesGraficos', function(event) {
        event.preventDefault();
    });

    $('#body-miembros').on('click', 'td.celda_de_descripcion', function() {

        var filaDeLaTabla = $(this).closest('tr');
        var filaComplementaria = objetoDataTables_Miembros.row(filaDeLaTabla);
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
        cadenaDeRetorno += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 0;">';
        cadenaDeRetorno += filaDelDataSet[9];
        cadenaDeRetorno += '</div>';

        return cadenaDeRetorno;
    }

    $('#btnAgregarMiembro').click(function() {
        $('#FormMiembro').each(function() {
            this.reset();
        });
        $("#btnAgregarFoto").prop("disabled", true);
        $("#idIglesia").val('1').trigger("chosen:updated");
        $("#comunidad").val('2').trigger("chosen:updated");
        listarProvincias(2)
        $("#provincia").val('').trigger("chosen:updated");
        $("#pais").val(195).trigger("chosen:updated");
        $("#profesion").val(0).trigger("chosen:updated");
        $("#status").val('1').trigger("chosen:updated");
        $("#nombreCorto").text('');
        $("#profesionCorto").text('');
        $("#fotoUsuario").html('<i class="img-thumbnail fa-8x far fa-user img-responsive avatar-view"></i> ')
        $("#ModalAgregarMiembro").modal('show');
        $("#TituloModalReferencia").html('<i class="text-primary fas fa-user-plus"></i> Agregar un nuevo Miembro..!')
        $("#fotoUsuario").html('<i class="img-thumbnail fa-8x far fa-user img-responsive avatar-view"></i> ')
        $("#adjuntosMiniatura").html('<center><img src="img/documentos.png" height="100" width="100"><br><h3 class="text-center">No tiene Documentos Adjuntos...</h3></center>');
        $("#grupoFamiliar").html('<center><img src="img/familia.png" height="100" width="100"><br><h3 class="text-center">No tiene grupo Familiar...</h3></center>');
        $("#home-tab").click();
    });

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


    $.validator.setDefaults({
        ignore: ":hidden:not(.chosen-select)"
    })

    $("#FormMiembro").validate({
        rules: {
            nombre: "required",
            apellido1: "required",
            nroDocumento: "required",
            direccion: "required",
            comunidad: "required",
            provincia: "required",
            poblacion: "required",
            lugNacimiento: "required",
            pais: "required",
            idIglesia: 'required'
        },
        messages: {
            nombre: "",
            apellido1: "",
            nroDocumento: "",
            direccion: "",
            comunidad: "",
            provincia: "",
            poblacion: "Indique Población.",
            lugNacimientos: "",
            pais: "",
            idIglesia: ''
        },

        submitHandler: function(form) {

            idIglesia = $("#idIglesia").val();

            if (idIglesia == '') {
                var el = $(document).find('[name="idIglesia"]');
                el.after($('<p class="errorDescripcion" style="color: #a94442;background-color: #f2dede;border-color: #ebccd1;padding:1px 20px 1px 20px;">Debe Selecionar una Iglesia</p>'));

            }

            alertify.confirm('Miembro', '<h4 class="text-info">Esta seguro de guardar estos datos..?</h4>', function() {
                var form = $('#FormMiembro');
                var formData = form.serialize();
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
                        alertify.success('Datos del Miembro actualizado....');
                    } else {
                        alertify.success('Error - no se pudo actualizar los datos del Miembro...');
                    }
                    $('#FormMiembro').each(function() {
                        this.reset();
                    });
                    objetoDataTables_Miembros.ajax.reload();
                    $('#ModalAgregarMiembro').modal('hide');

                }).fail(function(statusCode, errorThrown) {
                    $.unblockUI();
                    console.log(errorThrown);
                    ajaxError(statusCode, errorThrown);
                });

            }, function() { // En caso de Cancelar              
                alertify.error('Se Cancelo el Proceso para Guardar los datos del Miembro.');
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

    $('body').on('click', '#body-miembros a', function(e) {
        e.preventDefault();

        accion_ok = $(this).attr('data-accion');
        idMiembro = $(this).attr('idMiembro');

        switch (accion_ok) {

            case 'editarMiembro': // Edita Usuario
                loadingUI('Cargando datos para editar...', 'white')
                $.ajax({
                    url: 'editar-miembro',
                    type: 'get',
                    datatype: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        idMiembro: idMiembro
                    }
                }).fail(function(statusCode, errorThrown) {
                    console.log(statusCode + ' ' + errorThrown);
                }).done(function(response) {

                    console.log(response.archivosAdjuntos)
                    $("#btnAgregarFoto").prop("disabled", false);
                    $("#ModalAgregarMiembro").modal('show');
                    $("#TituloModalReferencia").html('<i class="fas fa-user-edit"></i> Editar datos del Miembro.')

                    $("#nombreCorto").text(response.data.nombre);
                    $("#profesionCorto").text(response.data.profesionNombre);

                    $("#idMiembro").val(response.data.id);
                    $("#idIglesia").val(response.data.idIglesia).trigger("chosen:updated");
                    $("#nombre").val(response.data.nombre);
                    $("#apellido1").val(response.data.apellido1);
                    $("#apellido2").val(response.data.apellido2);
                    $("#email").val(response.data.email);
                    $("#tipoDoc").val(response.data.tipoDocumento);
                    $("#nroDocumento").val(response.data.nroDocumento);
                    $("#sexo").val(response.data.sexo).trigger("chosen:updated");
                    $("#codPostal").val(response.data.codigoPostal);
                    $("#direccion").val(response.data.direccion);
                    $("#comunidad").val(response.data.comunidad).trigger("chosen:updated");
                    listarProvincias(response.data.comunidad,response.data.provincia);
                    $("#provincia").val(response.data.provincia).trigger("chosen:updated");
                    $("#poblacion").val(response.data.poblacion);
                    $("#telFijo").val(response.data.telefonoFijo);
                    $("#telMovil").val(response.data.telefonoMovil);
                    $("#fecNacimiento").val(response.data.fecNacimiento);
                    $("#edad").val(response.data.edad);
                    $("#lugNacimiento").val(response.data.lugarNacimiento);
                    $("#pais").val(response.data.paisNacimiento).trigger("chosen:updated");
                    $("#profesion").val(response.data.profesion).trigger("chosen:updated");
                    $("#fecBautismo").val(response.data.fecBautismo);
                    $("#iglesiaBautismo").val(response.data.iglesiaBautismo);
                    $("#cartaTraslado").val(response.data.fecCartaTraslado);
                    $("#iglesiaProcedencia").val(response.data.iglesiaProcedencia);
                    $("#otrosDatos").val(response.data.otrosDatos);
                    $("#status").val(response.data.status).trigger("chosen:updated");

                    if (response.data.foto === null || response.data.foto == "") {
                        $("#fotoUsuario").html('<i class="img-thumbnail fa-8x far fa-user img-responsive avatar-view"></i> ')
                    } else {
                        $("#fotoUsuario").html('<img class="img-thumbnail img-responsive avatar-view" src="img/fotos/' + response.data.foto + '" height="300" width="100">')
                    }

                    $("#adjuntosMiniatura").html(response.archivosAdjuntos);
                    $("#grupoFamiliar").html(response.grupoFamiliar);
                    $("#misMinisterios").html(response.ministerios);

                    $('[data-toggle="tooltip"]').tooltip()
                    $('#miembroToRelacion').empty();
                    array = Object.values(response.miembroToRelacion);
                    $("#miembroToRelacion").append('<option></option>');
                    $(array).each(function(i, v) {
                        $("#miembroToRelacion").append('<option value="' + v.id + '">' + v.nombre + ' ' + v.apellido1 + ' ' + v.apellido2 + '</option>');
                    })
                    $("#miembroToRelacion").trigger("chosen:updated");
                    $("#home-tab").click();
                    $.unblockUI();

                })

                break;

        }
    });

    //me listara las sub_areas dependiendo del area elegida es para el modulo de usuarios

    $("#comunidad").on('change', function() {
        idComunidad = $(this).val();
        listarProvincias(idComunidad);
    });

    $("#fecNacimiento").on('change', function() {
        fecNac = $("#fecNacimiento").val();

        $.ajax({
            url: 'calcular-fecha-nacimiento',
            type: 'get',
            datatype: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                fecNac: fecNac
            }
        }).fail(function(statusCode, errorThrown) {
            console.log(statusCode + ' ' + errorThrown);
        }).done(function(response) {
            console.log(response)
            $("#edad").val(response.data)
        })

    });


    function listarProvincias(idComunidad,idProvincia=null) {
        $.ajax({
            url: 'get-provincias',
            type: 'get',
            datatype: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                idComunidad: idComunidad
            }
        }).fail(function(statusCode, errorThrown) {
            console.log(statusCode + ' ' + errorThrown);
        }).done(function(response) {


            array = Object.values(response.data);

            if (response.success == true) {

                $('#provincia').empty();

                $(array).each(function(i, v) {
                    $("#provincia").append('<option value="' + v.id + '">' + v.nombre + '</option>');
                })

                if (idProvincia !==null){
                    $("#provincia").val(idProvincia);
                }

                $("#provincia").trigger("chosen:updated");
            } else {
                console.log("hubo un error");

            }
        })

    }

    $('#ModalAgregarFoto').on('shown.bs.modal', function() {
        iconoDropZone = '<br><br><i class="fa-4x fas fa-camera-retro"></i><br><h6>Click para agregar Foto.</h6>';
        configuraDropZone(iconoDropZone);
    })

    function configuraDropZone(iconoDropZone) {
        idMiembro = $("#idMiembro").val();
        Dropzone.autoDiscover = false;
        if (Dropzone.instances.length > 0) Dropzone.instances.forEach(bz => bz.destroy());
        $("#formDropZone").html('');
        $("#formDropZone").append("<form action='subir-foto' method='POST' files='true' enctype='multipart/form-data' id='dZUpload' class='dropzone borde-dropzone' style='width: 100%;padding: 0;cursor: pointer;'>" +
            "<div style='padding: 0;margin-top: 0em;' class='dz-default dz-message text-center'>" +
            iconoDropZone +
            "</div></form>");

        myAwesomeDropzone = myAwesomeDropzone = {
            maxFilesize: 12,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                return time + file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            timeout: 50000,
            maxFiles: 1,
            // removedfile: function(file) {
            //     var name = file.upload.filename;
            //     $.ajax({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            //         },
            //         type: 'POST',
            //         url: '{{ url("delete") }}',
            //         data: {
            //             filename: name
            //         },
            //         success: function(data) {
            //             console.log("File has been successfully removed!!");
            //         },
            //         error: function(e) {
            //             console.log(e);
            //         }
            //     });
            //     var fileRef;
            //     return (fileRef = file.previewElement) != null ?
            //         fileRef.parentNode.removeChild(file.previewElement) : void 0;
            // },
            params: {
                idMiembro: idMiembro
            },
            success: function(file, response) {
                console.log(response);
                fotoSubida = response.data
                $("#fotoUsuario").html('<img class="img-thumbnail img-responsive avatar-view" src="img/fotos/' + fotoSubida + '" height="300" width="100">')
            },
            error: function(file, response) {
                return false;
            }
        }

        var myDropzone = new Dropzone("#dZUpload", myAwesomeDropzone);

        myDropzone.on("queuecomplete", function(file, response) {
            if (Dropzone.instances.length > 0) Dropzone.instances.forEach(bz => bz.destroy());
            $('#ModalAgregarFoto').modal('hide');
        });
    }

    $(document).on('click', '.foto-miembro', function(event) {
        fileImage = $(this).attr('foto');
        if (fileImage == 'img\\fotos\\') {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error('<i class="fa-2x fas fa-photo-video"></i><br>Este miembro no tiene foto cargada.');
            return;
        }
        $("#ModalDetalleImagen").modal('show');
        imagen = '<img style="height: 23em;display: block;width: 100%" src="' + fileImage + '" alt="image" />'
        $("#divDetalleImagen").html(imagen);
    });

    $(document).on('click', '.imprimir-ficha', function(event) {
        idMiembro = $(this).attr('idMiembro');
        $.ajax({
            url: 'imprimir-ficha',
            type: 'get',
            data: {
                idMiembro: idMiembro,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                loadingUI('Generando ficha del miembro.');
            }
        }).done(function(response) {
            console.log(response)
            $("#modal-pdf").modal('show');
            $('#ObjPdf').attr('src', response);
            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
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


    $('#ModalAgregarDocumento').on('shown.bs.modal', function() {
        iconoDropZone = '<br><br><i class="fa-4x far fa-image"></i><br><h6>Click para agregar Documento.</h6>';
        configuraDropZoneDocumento(iconoDropZone);
    })

    function configuraDropZoneDocumento(iconoDropZone) {
        idMiembro = $("#idMiembro").val();
        Dropzone.autoDiscover = false;
        if (Dropzone.instances.length > 0) Dropzone.instances.forEach(bz => bz.destroy());
        $("#formDropZoneDocumento").html('');
        $("#formDropZoneDocumento").append("<form action='subir-documento' method='POST' files='true' enctype='multipart/form-data' id='dZUpload' class='dropzone borde-dropzone' style='width: 100%;padding: 0;cursor: pointer;'>" +
            "<div style='padding: 0;margin-top: 0em;' class='dz-default dz-message text-center'>" +
            iconoDropZone +
            "</div></form>");

        myAwesomeDropzone = myAwesomeDropzone = {
            maxFilesize: 12,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                return time + file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf",
            addRemoveLinks: true,
            timeout: 50000,
            maxFiles: 1,
            // removedfile: function(file) {
            //     var name = file.upload.filename;
            //     $.ajax({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            //         },
            //         type: 'POST',
            //         url: '{{ url("delete") }}',
            //         data: {
            //             filename: name
            //         },
            //         success: function(data) {
            //             console.log("File has been successfully removed!!");
            //         },
            //         error: function(e) {
            //             console.log(e);
            //         }
            //     });
            //     var fileRef;
            //     return (fileRef = file.previewElement) != null ?
            //         fileRef.parentNode.removeChild(file.previewElement) : void 0;
            // },
            params: {
                idMiembro: idMiembro
            },
            success: function(file, response) {
                console.log(response);
                fotoSubida = response.data
                $("#adjuntosMiniatura").html(response.data);
            },
            error: function(file, response) {
                return false;
            }
        }

        var myDropzone = new Dropzone("#dZUpload", myAwesomeDropzone);

        myDropzone.on("queuecomplete", function(file, response) {
            if (Dropzone.instances.length > 0) Dropzone.instances.forEach(bz => bz.destroy());
            $('#ModalAgregarDocumento').modal('hide');
        });
    }

    $(document).on('click', '.clickDelete', function(event) {
        event.preventDefault();

        fileImage = $(this).attr('nameFile');
        nameShort = $(this).attr('nameShort');
        nameFile = $(this).attr('nameFile');
        nameDate = $(this).attr('nameDate');
        archivo = $(this).attr('archivo');

        idMiembro = $("#idMiembro").val();

        alertify.confirm('<i class="text-danger far fa-trash-alt"></i><span class="text-danger"> Eliminar Documento</span>', '<h4 class="text-info">Esta seguro de Eliminar este Documento..?</h4><br><center><img src="' + archivo + '" height="100" width="100"></center>', function() {

            $.ajax({
                url: 'borrar-documento',
                type: 'get',
                data: {
                    idMiembro: idMiembro,
                    nombreOriginal: nameShort,
                    nameFile: nameFile

                },
                beforeSend: function() {
                    loadingUI('Actualizando');
                }
            }).done(function(response) {
                console.log(response)
                $("#adjuntosMiniatura").html(response.data);
                $.unblockUI();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });

        }, function() { // En caso de Cancelar              
            alertify.error('Se Cancelo el Proceso para Eliminar el Documento.');
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


    $(document).on('click', '.btnEliminarParentesco', function(event) {
        event.preventDefault();
        idMiembroParentesco = $(this).attr('idMiembroParentesco');
        idMiembro = $("#idMiembro").val();
        $.ajax({
            url: 'eliminar-parentesco',
            type: 'get',
            data: {
                idMiembro: idMiembro,
                idMiembroParentesco: idMiembroParentesco
            },
            beforeSend: function() {
                loadingUI('Eliminando parentesco del grupo familiar.');
            }
        }).done(function(response) {
            console.log(response)
            $("#ModalAgregarMiembroExistente").modal('hide');
            $("#grupoFamiliar").html(response.grupoFamiliar);
            $('[data-toggle="tooltip"]').tooltip()
            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });

    })

    $('#btnCerrar').click(function(event) {
        event.preventDefault();
        $("#ModalAgregarMiembro").modal('hide');
    });

    $('#btnAgregarRelacion').click(function() {

        idMiembro = $("#idMiembro").val();
        miembroToRelacion = $("#miembroToRelacion").val();
        parentesco = $("#parentesco").val();

        if (miembroToRelacion == "") {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error("Debe selecionar un miembro para agregarlo al grupo familiar.")
            $("#miembroToRelacion").focus();
            return
        }

        if (parentesco == "") {
            alertify.set('notifier', 'position', 'top-center');
            alertify.error("Debe selecionar un parentesco para el miembro seleccionado.")
            $("#parentesco").focus();
            return
        }

        $.ajax({
            url: 'agregar-familiar-existente',
            type: 'get',
            data: {
                idMiembro: idMiembro,
                miembroToRelacion: miembroToRelacion,
                parentesco: parentesco
            },
            beforeSend: function() {
                loadingUI('Agregando miembro al grupo familiar.');
            }
        }).done(function(response) {
            console.log(response)
            $("#ModalAgregarMiembroExistente").modal('hide');
            $("#grupoFamiliar").html(response.grupoFamiliar);
            $('[data-toggle="tooltip"]').tooltip()
            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
    });


});
