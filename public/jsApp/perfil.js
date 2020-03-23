$(document).on('ready', function() {

    loadImage();

    function loadImage() {
        $.ajax({
            url: 'buscar-imagen-usuario',
            type: 'get',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
            },
            // beforeSend: function() {
            //     loadingUI('Actualizando');
            // }
        }).done(function(data) {
            console.log(data)
            if (data.photo == '' || data.photo === null) {
                iconoDropZone = '<i class="fa-10x far fa-user-circle"></i><br><h6>Click para agregar Foto.</h6>';
                configuraDropZone(iconoDropZone);
            } else {
                iconoDropZone = '<img class="rounded-circle" src="/img/fotos/' + data.photo + '" style="width:100%;height:100%">';
                configuraDropZone(iconoDropZone);
            }

            $.unblockUI();

        }).fail(function(statusCode, errorThrown) {
            $.unblockUI();
            console.log(errorThrown);
            ajaxError(statusCode, errorThrown);
        });

    }


    function configuraDropZone(iconoDropZone) {

        Dropzone.autoDiscover = false;
        if (Dropzone.instances.length > 0) Dropzone.instances.forEach(bz => bz.destroy());
        $("#formDropZone").html('');
        $("#formDropZone").append("<form action='subir-foto-perfil' method='POST' files='true' enctype='multipart/form-data' id='dZUpload' class='dropzone borde-dropzone' style='width: 100%;padding: 0;cursor: pointer;'>" +
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
            params: {},
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

            if (Dropzone.instances.length > 0) Dropzone.instances.forEach(bz => bz.destroy());
            iconoDropZone = '<img class="rounded-circle" src="' + fotoSubida.data + '" style="width:100%;height:100%">';
            configuraDropZone(iconoDropZone);

        });
    }


});