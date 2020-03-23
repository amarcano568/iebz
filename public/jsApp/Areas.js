designCol = '';
objetoDataTables_personal = $('#TableListadoArea').DataTable();

muestraDataTables();

function muestraDataTables() {
    destroy_existing_data_table('#TableListadoArea');
    $.fn.dataTable.ext.pager.numbers_length = 4;
    objetoDataTables_personal = $('#TableListadoArea').DataTable({
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
        "iDisplayLength": 10,
        "ajax": {
            "method": "get",
            "url": "listar-areas",
            "data": {

            },
        },
        "initComplete": function(settings, json) {
            $.unblockUI();
            $(".chosen-select").chosen(configChosen());

        },
        "columns": [{
            "className": 'celda_de_descripcion',
            "orderable": false,
            "data": null,
            "defaultContent": '<a class="botonesGraficos" href=""><i class="fa-2x fa fa-plus-circle text-success" aria-hidden="true"></i></a>'
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
        }],
        "columnDefs": [{
            "width": "5%",
            "targets": 0
        }, {
            "width": "10%",
            "targets": 1
        }, {
            "width": "50%",
            "targets": 2
        }, {
            "width": "15%",
            "targets": 3,
            "className": "text-center",
        }, {
            "width": "15%",
            "targets": 4
        }],

    });
    objetoDataTables_personal.columns(4).visible(false);
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

$('#listaAreaOK').on('click', 'td.celda_de_descripcion', function() {

    var filaDeLaTabla = $(this).closest('tr');
    var filaComplementaria = objetoDataTables_personal.row(filaDeLaTabla);
    var celdaDeIcono = $(this).closest('td.celda_de_descripcion');

    if (filaComplementaria.child.isShown()) { // La fila complementaria está abierta y se cierra.
        filaComplementaria.child.hide();
        celdaDeIcono.html('<a class="botonesGraficos" href=""><i class="fa-2x fa fa-plus-circle text-success" aria-hidden="true"></i></a>');
    } else { // La fila complementaria está cerrada y se abre.
        filaComplementaria.child(formatearSalidaDeDatosComplementarios(filaComplementaria.data(), 2)).show();
        celdaDeIcono.html('<a class="botonesGraficos" href=""><i class="fa-2x fa fa-minus-circle text-danger" aria-hidden="true"></i></a>');
    }
});

function formatearSalidaDeDatosComplementarios(filaDelDataSet, columna) {
    var cadenaDeRetorno = '';

    cadenaDeRetorno += '<div class="row">';
    cadenaDeRetorno += '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="margin-top: -1em;">';
    cadenaDeRetorno += '<h3 class="text-danger"> Listado de Sub áreas.</h3>';
    cadenaDeRetorno += filaDelDataSet[3];
    cadenaDeRetorno += '</div>';

    return cadenaDeRetorno;
}

/*
    Eventos Click sobre la Table <grilla> de Area.
*/
$('body').on('click', '#listaAreaOK a', function(e) {
    e.preventDefault();

    accion_ok = $(this).attr('data-accion');
    Status = $(this).attr('status');
    Id = $(this).parents("tr").find("td")[1].innerHTML;

    Nombre = $(this).parents("tr").find("td")[2].innerHTML;

    if (accion_ok == "EditarArea") { // Ver Editar Categoria 
        $("#ModalAgregarArea").modal('show');

        $("#TitleModal").html('<i class="fas fa-edit"></i> Actualizar Área del Sistema..!');
        $("#IdAreaX").val(Id);
        $("#InputNombreArea").val(Nombre);
        $("#SelectStatus").val(Status).trigger("chosen:updated");
        $("#StatusArea").show();
        $("#DivIdArea").show();

    } else if ("inactivar | activar".indexOf(accion_ok) > -1) {
        var Selector = "#" + Id;
        $(Selector).show();
        $.ajax({ // Envío por Ajax para Activar o Desactivar la Categoria
            url: "Act-Des-Area",
            type: "get",
            dataType: "json",
            data: {
                'idArea': Id
            },
            success: function(data) {

                //alert(data)
                if (data.success === true) {
                    Pnotifica('Áreas.', data.mensaje, 'success', true);
                    objetoDataTables_personal.ajax.reload(null, false);
                } else {
                    Pnotifica('Áreas.', data.mensaje, 'error', true);
                }

                $.unblockUI();
                $(Selector).hide();
            },
            error: function(data) {
                alertify.error("Sucedió un Error Inesperado");
            }
        });

    } else if (accion_ok == "VerSubArea") {
        var Selector = "#" + Id;
        $(Selector).show();
        CargaTableSubArea(Id, Nombre);

    } else if (accion_ok == "AgregarSubArea") {
        var Selector = "#" + Id;
        //$(Selector).show();
        $("#ModalAgregarSubArea").modal('show');
        $("#TitleModalSubArea").text("Agregar SubCategoría del Sistema..!");
        $('#ModalSubArea').each(function() {
            this.reset();
        });
        $("#IdAreaAux").val(Id);
        $("#DivSubArea").hide();
        $("#SelectStatusSub").val('').trigger("chosen:updated");
    }

});

$.validator.setDefaults({
    ignore: ":hidden:not(.chosen-select)"
})

$("#ModalArea").validate({
    rules: {
        InputNombreArea: {
            required: true
        },
        SelectStatus: {
            required: true
        },

    },
    messages: {
        InputNombreArea: "Debe introducir el Nombre del Área.",
        SelectStatus: "Debe Seleccionar el Status del Área."
    },

    submitHandler: function(form) {

        var form = $('#ModalArea');
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
                Pnotifica('Área.', data.mensaje, 'success', true);
                objetoDataTables_personal.ajax.reload(null, false);
                $("#ModalAgregarArea").modal('hide');
            } else {
                Pnotifica('Área.', data.mensaje, 'error', true);
            }

            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });

        $("#ModalAgregarArea").modal('hide');
        $('#ModalArea').each(function() {
            this.reset();
        });


    }
});

// botón Agregar Categoría.
$('#BtnAgregarArea').click(function() {
    $("#ModalAgregarArea").modal('show');
    $('#ModalArea').each(function() {
        this.reset();
    });
    $("#SelectStatus").val('').trigger("chosen:updated");
    $("#DivIdArea").hide();
    $("#InputNombreArea").focus();
    $("#TitleModal").text("Agregar Área del Sistema..!")

});

/*
Eventos Click sobre la Table <grilla> de SubArea
    */
$('body').on('click', '#listaSubAreaOK a', function(e) {
    e.preventDefault();
    accion_ok = $(this).attr('data-accion');
    IdArea = $(this).attr('idArea');
    IdSubArea = $(this).attr('idSubArea');

    Nombre = $(this).attr('nombreSubArea');
    $Status = $(this).attr('status');

    if (accion_ok == "EditarSubArea") { // Ver Editar Categoria 
        $("#ModalAgregarSubArea").modal('show');

        $("#TitleModalSubArea").text("Actualizar SubArea del Sistema..!");
        $("#IdAreaAux").val(IdArea);
        $("#IdSubArea").val(IdSubArea);
        $("#InputNombreSubArea").val(Nombre);
        $("#SelectStatusSub").val($Status).trigger("chosen:updated");
        $("#StatusSubArea").show();
        $("#DivIdSubArea").show();

    } else if ("inactivarSubArea | activarSubArea".indexOf(accion_ok) > -1) {
        $.ajax({ // Envío por Ajax para Activar o Desactivar la Categoria
            url: "Act-Des-SubArea",
            type: "get",
            dataType: "json",
            data: {
                'idSubArea': IdSubArea
            },
            success: function(data) {

                //alert(data)
                if (data.success === true) {
                    Pnotifica('Áreas.', data.mensaje, 'success', true);
                    objetoDataTables_personal.ajax.reload(null, false);
                } else {
                    Pnotifica('Áreas.', data.mensaje, 'error', true);
                }

                $.unblockUI();
                $(Selector).hide();
            },
            error: function(data) {
                alertify.error("Sucedió un Error Inesperado");
            }
        });

    }


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

$("#ModalSubArea").validate({
    rules: {
        InputNombreSubArea: {
            required: true
        },
        SelectStatusSub: {
            required: true
        },
    },
    messages: {
        InputNombreSubArea: "Debe introducir el Nombre de la Subarea.",
        SelectStatusSub: "Debe Seleccionar el Status de la Subarea."
    },

    submitHandler: function(form) {

        var form = $('#ModalSubArea');
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
                Pnotifica('SubArea.', data.mensaje, 'success', true);
                objetoDataTables_personal.ajax.reload(null, false);
            } else {
                Pnotifica('SubArea.', data.mensaje, 'error', true);
            }

            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });

        $("#ModalAgregarSubArea").modal('hide');
        $('#ModalSubArea').each(function() {
            this.reset();
        });


    }
});