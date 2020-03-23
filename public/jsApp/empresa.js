$(document).on('ready', function() {

    var server = "";
    var pathname = document.location.pathname;
    var pathnameArray = pathname.split("/public/");
    var tituloimg = '';
    var descripcionImg = '';
    var objetoDataTables_Usuarios = '';

    server = pathnameArray.length > 0 ? pathnameArray[0] + "/public/" : "";

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

    $("#form_actualizar_empresa").validate({
        rules: {
            empresa: "required",
            ruc: "required",

        },
        messages: {
            empresa: "<i class='fas fa-exclamation-triangle'></i> Nombre de la empresa requerido.",
            ruc: "<i class='fas fa-exclamation-triangle'></i> Ruc requerido.",
        },

        submitHandler: function(form) {

            alertify.confirm('Empresa', '<h4 class="text-info">Esta seguro de guardar estos datos..?</h4>', function() {

                var form = $('#form_actualizar_empresa');
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
                        Pnotifica('Empresa.', data.mensaje, 'success', true);
                    } else {
                        Pnotifica('Empresa.', data.mensaje, 'error', false);
                    }

                }).fail(function(statusCode, errorThrown) {
                    $.unblockUI();
                    console.log(errorThrown);
                    ajaxError(statusCode, errorThrown);
                });

            }, function() { // En caso de Cancelar              
                alertify.error('Se Cancelo el Proceso para Actualizar los datos de la empresa.');
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

    $("#form_actualizar_correo").validate({
        rules: {
            nombreEmail: "required",
            smtpEmail: "required",
            portEmail: {
                required: true,
                number: true
            },
            encryptionEmail: "required",
            correoEmail: "required",
            passwordEmail: "required"
        },
        messages: {
            nombreEmail: "<i class='fas fa-exclamation-triangle'></i> Nombre Servicio requerido.",
            smtpEmail: "<i class='fas fa-exclamation-triangle'></i> Smtp requerido.",
            portEmail: {
                required: "<i class='fas fa-exclamation-triangle'></i> Port requerido.",
                number: "<i class='fas fa-exclamation-triangle'></i> Solo múmeros."
            },
            encryptionEmail: "<i class='fas fa-exclamation-triangle'></i> Encryption requerido.",
            correoEmail: "<i class='fas fa-exclamation-triangle'></i> Correo requerido.",
            passwordEmail: "<i class='fas fa-exclamation-triangle'></i> Password requerido."
        },

        submitHandler: function(form) {

            alertify.confirm('Configuración correo.', '<h4 class="text-info">Esta seguro de guardar estos datos..?</h4>', function() {

                var form = $('#form_actualizar_correo');
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
                        Pnotifica('Correo.', data.mensaje, 'success', true);
                    } else {
                        Pnotifica('Correo.', data.mensaje, 'error', false);
                    }

                }).fail(function(statusCode, errorThrown) {
                    $.unblockUI();
                    console.log(errorThrown);
                    ajaxError(statusCode, errorThrown);
                });

            }, function() { // En caso de Cancelar              
                alertify.error('Se Cancelo el Proceso para Actualizar los datos del Correo.');
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


});