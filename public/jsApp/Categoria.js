designCol = '';
objetoDataTables_personal = $('#tableCategorias').DataTable();

muestraDataTables();

function destroy_existing_data_table(tableDestry) {
    var existing_table = $(tableDestry).dataTable();
    if (existing_table != undefined) {
        existing_table.fnClearTable();
        existing_table.fnDestroy();
    }
}

function muestraDataTables() {
    destroy_existing_data_table('#tableCategorias');
    $.fn.dataTable.ext.pager.numbers_length = 4;
    objetoDataTables_personal = $('#tableCategorias').DataTable({
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
            "sLengthMenu": "Mostrar _MENU_ Categorías",
            "sZeroRecords": "No se encontró ningun Registro con la Condición del Filtro",
            "sEmptyTable": "Ningun Registro Agregado aún...",
            "sInfo": "Del _START_ al _END_ de un total de _TOTAL_ Registro",
            "sInfoEmpty": "De 0 al 0 de un total de 0 Registro",
            "sInfoFiltered": "(filtrado de un total de _MAX_ Registros)",
            "sInfoPostFix": "",
            "sSearch": "",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": loadingUI('Cargando listado de Miembros...', 'white'),
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
        "iDisplayLength": 10,
        "ajax": {
            "method": "get",
            "url": "listar-categoria",
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
            //console.log(dataFilter)
            objetoDataTables_personal.search(dataFilter).draw();
            $(".chosen-select").chosen(configChosen());
            $('#idAreaColumn').val(dataFilter);
            $('#idAreaColumn').trigger("chosen:updated");

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
        }],
        "columnDefs": [{
            "width": "2.5%",
            "targets": 0
        }, {
            "width": "5%",
            "targets": 1
        }, {
            "width": "25%",
            "targets": 2
        }, {
            "width": "25%",
            "targets": 3
        }, {
            "width": "25%",
            "targets": 4
        }, {
            "width": "10%",
            "targets": 5,
            "className": "text-center"
        }, {
            "width": "5%",
            "targets": 6,
            "className": "ancho"
        }],

    });
    objetoDataTables_personal.columns(6).visible(false);
    objetoDataTables_personal.columns(7).visible(false);
}

function designColumn(table) {
    table.api().columns([3, 4]).every(function() {
        var column = this;
        if (column[0] == 3) {
            placeholder = 'Selecione el Área...';
        } else if (column[0] == 4) {
            placeholder = 'Selecione el Tipo de Ticket...';
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
    objetoDataTables_personal.search('').draw();
});

function configChosen() {

    return ({
        no_results_text: 'No hay resultados para ',
        placeholder_text_single: 'Seleccione una Opción',
        disable_search_threshold: 10

    })
}

$(document).on('click', '.botonesGraficos', function(event) {
    event.preventDefault();
});

$('#bodyCategorias').on('click', 'td.celda_de_descripcion', function() {

    var filaDeLaTabla = $(this).closest('tr');
    var filaComplementaria = objetoDataTables_personal.row(filaDeLaTabla);
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
    cadenaDeRetorno += '<h4 class="text-info"><i class="fa fa-list-alt" aria-hidden="true"></i> Listado de Subcategorías:</h4>';
    cadenaDeRetorno += filaDelDataSet[5];
    cadenaDeRetorno += '</div>';
    cadenaDeRetorno += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 0;"><button class="btn btn-white btn-default btn-round botonAgregarAplicacion" action="' + filaDelDataSet[6] + '" style="float: right;"><i class="ace-icon fa fa-plus-circle green"></i></button>';
    cadenaDeRetorno += '<h4 class="text-info"><i class="fa fa-list-alt" aria-hidden="true"></i> Listado de Aplicación <strong style="text-danger" id="tituloAplicacion' + filaDelDataSet[6] + '"></strong></h4>';
    cadenaDeRetorno += '<div id="Aplicacion' + filaDelDataSet[6] + '"></div>';
    cadenaDeRetorno += '</div>';
    cadenaDeRetorno += '</div>';

    return cadenaDeRetorno;
}


function CargaTableCategoria() {
    $.fn.dataTable.ext.pager.numbers_length = 4;
    $('#TableListadoCategoria').DataTable(ConfigSpanishCategoria());
    $('#TableSubCategoria').DataTable(ConfigSpanishSubCategoria());
    $('#BtnAgregarCategoria').show();
}

function CargaTableSubCategoria(IdCategoria, DescCategoria) {

    $('#DivAminSubCategoria').load('FuncionSubCategoria.inc.php?Categoria=' + IdCategoria, function() {
        $("#DescSubCategoria").text(DescCategoria);
        $("#" + IdCategoria).hide();
        $("#espera_datos2").hide();
        $.fn.dataTable.ext.pager.numbers_length = 4;
        $('#TableListadoSubCategoria').DataTable().destroy();
        $('#TableListadoSubCategoria').DataTable(ConfigSpanishSubCategoria());
    });
}


$(".chosen-select").chosen({
    no_results_text: "Vaya, No Encontró Nadaaaaa!"
});


// botón Agregar Categoría.
$('#BtnAgregarCategoria').click(function() {
    $("#ModalAgregarCategoria").modal('show');

    $("#TitleModal").text("Agregar Categoría del Sistema..!")
    $("#StatusCategoria").hide();
    $("#DivIdCategoria").hide();
});

$('#ModalAgregarCategoria').on('shown.bs.modal', function() {
    $('.chosen-select', this).chosen('destroy').chosen(configChosen());
    $('#SelectStatus').chosen('destroy').chosen(configChosen());

});

function configChosen() {
    return {
        disable_search_threshold: 10,
        allow_single_deselect: true,
        width: "100%",
        no_results_text: "Oops, no encontrado!"
    }
}

$.validator.setDefaults({
    ignore: ":hidden:not(.chosen-select)"
})

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

$("#ModalCategoria").validate({
    rules: {
        InputNombreCategoria: {
            required: true,
            minlength: 2
        },
        SelectArea: {
            required: true
        },
        SelectTipo: {
            required: true
        },

    },
    messages: {
        InputNombreCategoria: "Debe introducir el Nombre de la Categoría.",
        SelectArea: "Debe Seleccionar el Área de la Categoría.",
        SelectTipo: "Debe selecionar el tipo del Ticket."

    },

    submitHandler: function(form) {
        var id = $("#IdUser").val();
        var accionAux = $("#TitleModal").text();
        accionAux = accionAux.split(" ");
        var accion = accionAux[0];
        console.log($("#SelectTipo").val());

        var form = $('#ModalCategoria');
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
            if (data.success === true) {
                Pnotifica('Categoría.', data.mensaje, 'success', true);
                objetoDataTables_personal.ajax.reload(null, false);
            } else {
                Pnotifica('Categoría.', data.mensaje, 'error', true);
            }

            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });
        // var formData = new FormData(document.getElementById("ModalCategoria"));
        // formData.append("opcion", accion);
        // formData.append("id", id);
        // $.ajax({
        //         url: "CategoriaFuncgral.php",
        //         type: "post",
        //         dataType: "html",
        //         data: formData,
        //         cache: false,
        //         contentType: false,
        //         processData: false,
        //         success: function(data) {
        //             //alert(data)
        //             data = data.split(';');
        //             if (data[0] == 1) {
        //                 alertify.success("Registro Agregado <br>" + data[1]);
        //             } else if (data[0] == 2) {
        //                 alertify.success("Registro Actualizado <br>" + data[1]);
        //             } else if (data[0] == 0) {
        //                 MensajeGritter("Error", data[1], "gritter-error");
        //             }
        //             objetoDataTables_personal.ajax.reload();
        //             designColumn(designCol);
        //             $(".chosen-select").chosen(configChosen());
        //             $('#idAreaColumn').val(dataFilter);
        //             $('#idAreaColumn').trigger("chosen:updated");
        //         },
        //         error: function(data) {
        //             MensajeGritter("Error", "Sucedió un Error Inesperado", "gritter-error");
        //         }
        //     })
        //     .done(function(res) {
        //         //$("#mensaje").html("Respuesta del Host: " + res);
        //     });

        $("#ModalAgregarCategoria").modal('hide');
        $('#ModalCategoria').each(function() {
            this.reset();
        });


    }
});


/*
         Eventos Click sobre la Table <grilla> de Categoría.
    */
$('body').on('click', '#bodyCategorias a', function(e) {
    e.preventDefault();

    accion_ok = $(this).attr('data-accion');
    idAreaVal = $(this).attr('idArea');
    statusCat = $(this).attr('statusCat');
    tipTicket = $(this).attr('tipTicket');
    idTabla = $(this).attr('idTabla');
    Id = $(this).parents("tr").find("td")[1].innerHTML;
    Nombre = $(this).parents("tr").find("td")[2].innerHTML;
    IdArea = $(this).parents("tr").find("td")[3].innerHTML;
    AreaNombre = $(this).parents("tr").find("td")[4].innerHTML;
    $Status = $(this).parents("tr").find("td")[5].innerHTML;

    if ($Status == '<span class="badge badge-success">Activo</span>') {
        $Status = '1';
    } else {
        $Status = '0';
    }

    if (accion_ok == "EditarCategoria") { // Ver Editar Categoria
        $("#ModalAgregarCategoria").modal('show');
        $("#TitleModal").html('<i class="far fa-edit"></i> Actualizar Categoría del Sistema..!');
        $("#IdCategoria").val(Id);
        $("#InputNombreCategoria").val(Nombre);
        $("#SelectArea").val(idAreaVal);
        $("#SelectTipo").val(idTabla);
        $("#SelectStatus").val(statusCat);
        $("#StatusCategoria").show();
        $("#DivIdCategoria").show();

    } else if ("inactivar | activar".indexOf(accion_ok) > -1) { // Bloquea Activa o Desactiva Categoria
        alertify.confirm('Cambiar de Status esta Categoría', '<h5 class="text-danger">Esta seguro de hacer este cambio <i class="fa-2x fa fa-question-circle-o" aria-hidden="true"></i></h5>', function() {
            var Selector = "#" + Id;
            $(Selector).show();

            $.ajax({
                url: 'Act-Des-Categoria',
                type: 'get',
                data: {
                    "idCategoria": Id
                },
                beforeSend: function() {
                    loadingUI('Actualizando');
                }
            }).done(function(data) {

                console.log(data)

                if (data.success === true) {
                    Pnotifica('Categoría.', data.mensaje, 'success', true);
                    objetoDataTables_personal.ajax.reload(null, false);
                } else {
                    Pnotifica('Categoría.', data.mensaje, 'error', true);
                }

                $("#ModalAgregarAplicacion").modal('hide');
                $.unblockUI();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });

        }, function() { // En caso de Cancelar              
            alertify.error('Se Cancelo el Proceso para cambiar de status la Categoría');
        }).set('labels', {
            ok: 'Confirmar',
            cancel: 'Cancelar'
        }).set({
            transition: 'zoom'
        }).set({
            modal: true,
            closableByDimmer: false
        });
    } else if (accion_ok == "VerSubCategoria") {
        var Selector = "#" + Id;
        $(Selector).show();
        CargaTableSubCategoria(Id, Nombre);

    } else if (accion_ok == "AgregarSubCategoria") {
        // var Selector = "#" + Id;
        // $(Selector).show();
        $("#ModalAgregarSubCategoria").modal('show');
        $("#TitleModalSubCategoria").text("Agregar SubCategoría del Sistema..!");
        $("#IdCategoriaAux").val(Id);

    }


});

$("#ModalSubCategoria").validate({
    rules: {
        InputNombreSubCategoria: {
            required: true,
            minlength: 2
        },
    },
    messages: {
        InputNombreSubCategoria: "Debe introducir el Nombre de la SubCategoría.",

    },
    submitHandler: function(form) {
        var id = $("#IdUser").val();
        var nombre = $("#InputNombreSubCategoria").val();
        var IdCategoriaAux = $("#IdCategoriaAux").val();

        var form = $('#ModalSubCategoria');
        var formData = form.serialize() + "&id=" + id;
        var route = form.attr('action');

        $.ajax({
            url: "registrar-sub-categoria",
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
                objetoDataTables_personal.ajax.reload();
            } else {
                Pnotifica('Tipo de Ticket', data.mensaje, 'error', true);
            }

            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });

        $("#ModalAgregarSubCategoria").modal('hide');
        $('#ModalSubCategoria').each(function() {
            this.reset();
        });


    }
})

/*
Eventos Click sobre la Table <grilla> de SubCategoría.
    */
$('body').on('click', '#listaSubCategoriaOK a', function(e) {
    e.preventDefault();

    accion_ok = $(this).attr('data-accion');
    IdSubCat = $(this).attr('id-subCat');
    nomSubCat = $(this).attr('nomSubCat');
    IdCat = $(this).attr('id-Cat');
    Nombre = $(this).attr('nomCat');
    $Status = $(this).attr('status');

    if ($Status == 'Activo') {
        $Status = '1';
    } else {
        $Status = '0';
    }

    if (accion_ok == "EditarSubCategoria") { // Ver Editar Categoria 

        $("#ModalAgregarSubCategoria").modal('show');
        $("#TitleModalSubCategoria").text("Actualizar SubCategoría del Sistema..!");
        $("#IdCategoriaAux").val(IdCat);
        $("#IdSubCategoria").val(IdSubCat);
        $("#InputNombreSubCategoria").val(Nombre);
        $("#SelectStatusSub").val($Status).chosen(configChosen());
        $("#StatusSubCategoria").show();
        $("#DivIdSubCategoria").show();

    } else if (accion_ok == "verAplicacion") {
        $("#TableListadoSubCategoria tbody tr").removeClass("select-row");
        var row_edit = $(this).parent().parent();
        row_edit.addClass('select-row');
        var data = {
            'idSubCategoria': IdSubCat
        };
        $.get('listar-aplicacion', data)
            .done(function(response) {
                $("#Aplicacion" + IdCat).html(response);
                $("#tituloAplicacion" + IdCat).text(nomSubCat + ' - ' + IdSubCat);
                $("#Aplicacion" + IdCat).fadeIn(3000, function() {
                    $("span").fadeIn(100);
                })
            });
    } else if ("inactivarSubCategoria | activarSubCategoria".indexOf(accion_ok) > -1) { // Bloquea Activa o Desactiva Categoria
        alertify.confirm('Cambiar de Status esta Subcategoría', '<h5 class="text-danger">Esta seguro de hacer este cambio <i class="fa-2x fa fa-question-circle-o" aria-hidden="true"></i></h5>', function() {

            var Selector = "#" + IdSubCat;
            $(Selector).show();

            $.ajax({
                url: "Act-Des-SubCategoria",
                type: 'get',
                data: {
                    'IdSubCat': IdSubCat
                },
                beforeSend: function() {
                    //loadingUI('Actualizando');
                }
            }).done(function(data) {

                console.log(data)

                if (data.success === true) {
                    Pnotifica('Subcategoría', data.mensaje, 'success', true);
                    objetoDataTables_personal.ajax.reload(null, false);
                } else {
                    Pnotifica('Subcategoría', data.mensaje, 'error', true);
                }

                $.unblockUI();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });


        }, function() { // En caso de Cancelar              
            alertify.error('Se Cancelo el Proceso para cambiar de status la Subcategoría');
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


$(document).on('click', '.botonAgregarAplicacion', function(event) {
    table = $(this).attr('action');

    if ($("#Aplicacion" + table).html() == "") {
        alertify.set('notifier', 'position', 'bottom-right');
        alertify.error('<i class="fa-3x fa fa-exclamation-triangle" aria-hidden="true"></i><br>Debe seleccionar una Sub categoría...');
        return false;
    }

    idSubCategoria = $("#tituloAplicacion" + table).text();

    idSubCategoria = idSubCategoria.split(' - ');

    $("#idAplicacion").val('');
    $("#IdSubCategoriaAplicacion").val(idSubCategoria[1]);

    $("#ModalAgregarAplicacion").modal('show');
    $("#TitleModalAplicacion").text('Agregar Aplicación.');
});

//InputNombreAplicacion

$('#ModalAgregarAplicacion').on('shown.bs.modal', function() {
    $('#InputNombreAplicacion').focus();
});

$("#FormModalAplicacion").validate({
    rules: {
        InputNombreAplicacion: {
            required: true,
            minlength: 2
        },
    },
    messages: {
        InputNombreAplicacion: {
            required: "Debe introducir el Nombre de la Aplicación.",
            minlength: "Min. dos (2) caracteres."
        }
    },
    submitHandler: function(form) {

        var form = $('#FormModalAplicacion');
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

            if (data.success === true) {
                Pnotifica('Aplicación.', data.mensaje, 'success', true);
                objetoDataTables_personal.ajax.reload(null, false);
            } else {
                Pnotifica('Aplicación.', data.mensaje, 'error', true);
            }

            $("#ModalAgregarAplicacion").modal('hide');
            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });

        $('#FormModalAplicacion').each(function() {
            this.reset();
        });


    }
});

/*
Eventos Click sobre la Table <grilla> de Aplicación.
    */
$('body').on('click', '.BodyAplicacionOK a', function(e) {
    e.preventDefault();

    accion_ok = $(this).attr('data-accion');
    idAplicacion = $(this).attr('idAplicacion');
    status = $(this).attr('status');
    descripcion = $(this).attr('descripcion');
    idSubCat = $(this).attr('idSubCat');

    if (accion_ok == "EditarPrbFrecuente") { // Ver Editar Categoria 

        $("#idAplicacion").val(idAplicacion);
        $("#IdSubCategoriaAplicacion").val(idSubCat);
        $("#InputNombreAplicacion").val(descripcion);
        $("#SelectStatusAplicacion").val(status).chosen(configChosen());

        $("#ModalAgregarAplicacion").modal('show');
        $("#TitleModalAplicacion").text('Editar Aplicación.');
    }

});

//se agrego nuevo change para capturar tickes de acuerdo al area seleccionada 

// $(document).on('change', '#SelectArea', function(event) {
//     var Area = $("#SelectArea").val();
//     FuncSelectTipoTicket(Area);

// });

// function FuncSelectTipoTicket(IdArea) {
//     $.ajax({
//         url: 'listar-tipo_ticket',
//         type: 'get',
//         dataType: 'json',
//         data: {
//             idArea: IdArea,
//             _token: "{{ csrf_token() }}",
//         },
//         beforeSend: function() {
//             loadingUI('Actualizando');
//         }
//     }).done(function(data) {

//         console.log(data.data)
//         $("#SelectTipo").empty();
//         arrayTipoTickets = data.data;
//         $(arrayTipoTickets).each(function(i, data1) {
//             $("#SelectTipo").append('<option value="' + data1.idTabla + '">' + data1.desTabla + '</option>');
//         });
//         $("#SelectTipo").trigger("chosen:updated");


//         $.unblockUI();

//     }).fail(function(statusCode, errorThrown) {
//         $.unblockUI();
//         console.log(errorThrown);
//         ajaxError(statusCode, errorThrown);
//     });

// };