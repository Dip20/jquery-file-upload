<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">


    <style>
        .fileUpload {
            position: relative;
            overflow: hidden;
        }

        .fileUpload input {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            cursor: pointer;
            opacity: 0;
        }

        .progress {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
    <!-- <script async src="//jsfiddle.net/lovlka/N4Jxk/embed/"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>


    <br><br>

    <form>
        <span class="fileUpload btn btn-default">
            <span class="glyphicon glyphicon-upload"></span> Upload file
            <input type="file" id="uploadFile" />
        </span>
    </form>

    <p id="fileUploadError" class="text-danger hide"></p>

    <div class="list-group" id="files"></div>

    <div id="fileUploadProgressTemplate" type="text/x-jquery-tmpl">
        <div class="list-group-item">
            <div class="progress progress-striped active">
                <div class="progress-bar progress-bar-info" style="width: 0%;"></div>
            </div>
        </div>
    </div>

    <div id="fileUploadItemTemplate" type="text/x-jquery-tmpl">
        <div class="list-group-item">
            <button type="button" class="close">&times;</button>
            <span class="glyphicon glyphicon-file"> File name (type, date)</span>
        </div>
    </div>



    <script>
        $("#uploadFile").change(function() {
            var formData = new FormData();
            formData.append('file', this.files[0]);

            $("#files").append($("#fileUploadProgressTemplate").tmpl());
            $("#fileUploadError").addClass("hide");

            $.ajax({
                url: './uploader.php',
                type: 'POST',
                dataType: "json",
                xhr: function() {
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(evt) {
                            var percent = (evt.loaded / evt.total) * 100;
                            $("#files").find(".progress-bar").width(percent + "%");
                        }, false);
                    }
                    return xhr;
                },
                success: function(data) {
                    if (data.status == "success") {
                        $("#files").children().last().remove();
                        $("#files").append($("#fileUploadItemTemplate").tmpl(data));
                        $("#uploadFile").closest("form").trigger("reset");
                        var data = data.data;
                        var mssg = `FileName: ${data.filename}, FileType: ${data.file_type}, FileSize: ${data.file_size}`;
                        $(".glyphicon-file").text(mssg);
                    } else {
                        $("#fileUploadError").removeClass("hide").text(data.msg);
                        $("#files").children().last().remove();
                        $("#uploadFile").closest("form").trigger("reset");
                    }


                },
                error: function(error) {
                    console.log(error.responseText);
                    $("#fileUploadError").removeClass("hide").text(error.responseText);
                    $("#files").children().last().remove();
                    $("#uploadFile").closest("form").trigger("reset");
                },
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }, 'json');
        });
    </script>



</body>

</html>