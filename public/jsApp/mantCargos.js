$(document).on('ready', function() {

    objetoDataTables_Cargos = $('#tableCargos');

    muestraDataTables();

    function muestraDataTables() {

        objetoDataTables_Cargos.DataTable({
            ajax: {
                url: 'listar-cargos',
                type: 'GET',
                dataSrc: '',
                dataType: 'json'

            },

            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ filas",
                "infoEmpty": "Mostrando 0 to 0 of 0 filas",
                "infoFiltered": "(Filtrado de _MAX_ total filas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ filas",
                "loadingRecords": "Cargando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            columns: [{
                    data: 'idCargo'
                }, {
                    data: 'descCargo'
                }, {
                    //data: 'activo'
                    data: null,
                    "render": function(data, type, full, meta) {

                        if (data.activo == 1) {

                            return '<center><span class="badge badge-pill badge-success"><i class="fas fa-check"> </i>Activo</span></center>';

                        } else {

                            return '<center><span class="badge badge-pill badge-danger"><i class="fas fa-ban"></i>Inactivo</span></center>';
                        }

                    }
                },


                {
                    data: null,
                    "render": function(data, type, full, meta) {
                        return '<center>' +
                            '<span>&nbsp;&nbsp;&nbsp;</span>' +
                            '<a onclick="prepareUpdatedCargo(\'' + data.idCargo + '\',\'' + data.descCargo + '\',\'' + data.activo + '\')" style="cursor:pointer">' +
                            '<i class="far fa-edit"></i>' +
                            '</a>' +
                            '</center>';
                    }
                }
            ],
            "success": function(data) {
                if (data.activo == 1) {
                    alert("se reemplazara");
                }
            }
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

    $("#FormMantCargo").validate({
        rules: {
            descCargo: {
                required: true,
                minlength: 5
            }

        },
        messages: {
            descCargo: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Debe introducir Descripción para el cargo.'
        },
        submitHandler: function(form) {

            var form = $('#FormMantCargo');
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


                $('#tableCargos').DataTable().ajax.reload();

                if (data.success === true) {
                    Pnotifica('Tipo de Cargo', data.mensaje, 'success', true);
                } else {
                    Pnotifica('Tipo de Cargo', data.mensaje, 'error', true);
                }

                $.unblockUI();

            }).fail(function(statusCode, errorThrown) {
                $.unblockUI();
                console.log(errorThrown);
                ajaxError(statusCode, errorThrown);
            });


            $("#ModalMantCargo").modal('hide');
            $('#FormMantCargo').each(function() {
                this.reset();
            });
        }
    })

})

function createNewCargo() {
    $('#ModalMantCargo').modal('show');
    $('#FormMantCargo').each(function() {
        this.reset();
    });

}

function prepareUpdatedCargo(idCargo, descripcion, activo) {
    $('#ModalMantCargo').modal('show');
    $('#descCargo').val(descripcion);
    $('#statusCargo').val(activo);
    $('#idCargo').val(idCargo);

}