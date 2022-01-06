    <!--
    <script>
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone(".dropzone", {
            autoProcessQueue: false,
            maxFilesize: 3,
            maxFiles: 10,
            clickable: false

            //    acceptedFiles: ".jpeg,.jpg,.png,.gif"
        });

        $('#send_files').click(function(e) {
            e.preventDefault();
            myDropzone.processQueue();
        });




        myDropzone.on("addedfile", file => {
            var unique_field_id = new Date().getTime();
            title = file.title == undefined ? "" : file.title;

            file.test = Dropzone.createElement(file.fullPath)

            file._titleLabel = Dropzone.createElement("<p>Tytuł:</p>")
            file._titleBox = Dropzone.createElement("<input id='" + file.name + unique_field_id + "_title' type='text' name='title' value=" + title + " >");
            file._timeFromLabel = Dropzone.createElement("<p>Czas od:</p>")
            file._timeFromBox = Dropzone.createElement("<input id='" + file.name + unique_field_id + "_timeFrom' type='time' name='timeFrom' value=0:15 >");
            file._timeToLabel = Dropzone.createElement("<p>Czas od:</p>")
            file._timeToBox = Dropzone.createElement("<input id='" + file.name + unique_field_id + "_timeTo' type='time' name='timeTo' value=0:30 >");
            file.previewElement.appendChild(file.test);
            file.previewElement.appendChild(file._titleLabel);
            file.previewElement.appendChild(file._titleBox);
            file.previewElement.appendChild(file._timeFromLabel);
            file.previewElement.appendChild(file._timeFromBox);
            file.previewElement.appendChild(file._timeToLabel);
            file.previewElement.appendChild(file._timeToBox);

        });

        myDropzone.on('sending', function(data, xhr, formData) {
            title = data.previewElement.querySelector("input[name='title']").value;
            timeFrom = data.previewElement.querySelector("input[name='timeFrom']").value;
            timeTo = data.previewElement.querySelector("input[name='timeTo']").value;
            alert(timeFrom);
            formData.append("title", title);
            formData.append("timeFrom", timeFrom);
            formData.append("timeTo", timeTo);


            // this won't -- we don't need this rn, we can just use jQuery
            // var myForm = document.querySelector('form');

            // you are overwriting your formdata here.. remove this
            //formData = new FormData(myForm);

            // instead, just append the form elements to the existing formData
            // $("form").find("input").each(function() {
            //     formData.append($(this).attr("name"), $(this).val());
            // });
        });
    </script>
    -->