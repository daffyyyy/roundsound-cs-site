<?php

$pack_id = md5(time() . uniqid());

require_once 'inc/Roundsound.class.php';
$class = new Roundsound();
$random = $class->greaterThanCount();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>:: Tworzenie roundsound :: daffyy.pl</title>

    <link rel="stylesheet" href="vendor/dropzonejs/dropzone.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='shortcut icon' href='https://utopiafps.pl/uploads/monthly_2021_12/utopiafps-favicon.png' type="image/png">
</head>

<body>
    <div class="container">
        <h1 class="mt-3">Tworzenie roundsound</h1>
        <p>Dodaj lub przeciągnij pliki <strong>MP3</strong> i określ ich nazwę i długość. <br />Po dodaniu i ustawieniu piosenek, kliknij <strong>Rozpocznij tworzenie</strong> i poczekaj na wygenerowanie paczki.</p>
        <div id="actions" class="row">
            <div class="col-lg-7">
                <?php if ($random) : ?>
                    <a href="download.php?random=5" class="btn btn-secondary">
                        <i class="fas fa-random"></i>
                        <span>Pobierz losowe</span>
                    </a>
                <?php endif; ?>
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button dz-clickable">
                    <i class="fas fa-plus-circle"></i>
                    <span>Dodaj pliki</span>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="far fa-play-circle"></i>
                    <span>Rozpocznij tworzenie</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="fas fa-window-close"></i>
                    <span>Anuluj tworzenie</span>
                </button>
            </div>

            <div class="col-lg-5">
                <!-- The global file processing state -->
                <span class="fileupload-process">
                    <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress=""></div>
                    </div>
                </span>
            </div>
        </div>
        <div class="table table-striped files" id="previews">
            <div id="template" class="file-row dz-image-preview">
                <!-- This is used as the file preview template -->
                <div>
                    <span class="preview"><img data-dz-thumbnail></span>
                </div>
                <div>
                    <p class="name" data-dz-name></p>
                    <strong class="error text-danger" data-dz-errormessage></strong>
                </div>
                <div>
                    <div class="input-group mb-3">
                        <input name="title" type="text" class="form-control" placeholder="Tytuł" aria-label="Tytuł">
                        <input name="time" type="text" class="form-control" placeholder="Czas Od:Czas Do" aria-label="Time" value="00:15-00:30">
                    </div>
                    <!-- <button class="btn btn-primary start">
                <i class="glyphicon glyphicon-upload"></i>
                <span>Wyślij</span>
            </button>
            <button data-dz-remove class="btn btn-warning cancel">
                <i class="glyphicon glyphicon-ban-circle"></i>
                <span>Anuluj</span>
            </button> -->
                </div>
            </div>
        </div>
        <a id="download" style="display: none;" href="download.php?pack_id=<?php echo $pack_id; ?>" class="btn btn-primary">Pobieranie paczki</a>
    </div>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Stworzone przez <strong>DaFFyy</strong> z dużą ilością <i style="color: orange;" class="fas fa-cookie"></i> dla <a href="https://roundsound.cs.daffyy.pl">roundsound.cs.daffyy.pl</a> &copy;<?php echo date('Y');?> </p>
                </div>
            </div>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="vendor/dropzonejs/dropzone.min.js"></script>

    <script>
        function showLink() {
            $('#download').show();
        }

        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        let myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "upload.php", // Set the url
            acceptedFiles: ".mp3",
            maxFiles: 10,
            maxSize: 8,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
        });

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            // file.previewElement.querySelector(".start").onclick = function() {
            //     myDropzone.enqueueFile(file);
            // };
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            // document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
        });

        myDropzone.on("sending", function(file, xhr, formData) {
            title = file.previewElement.querySelector("input[name='title']").value;
            time = file.previewElement.querySelector("input[name='time']").value;
            session_id = file.previewElement.querySelector("input[name='title']").value;
            formData.append("title", title);
            formData.append("time", time);
            formData.append("pack_id", '<?php echo $pack_id; ?>');
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1";
            // And disable the start button
        });

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0";
            setTimeout(showLink, 3500);
        });

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
        };
        document.querySelector("#actions .cancel").onclick = function() {
            myDropzone.removeAllFiles(true);
        };
    </script>
</body>

</html>