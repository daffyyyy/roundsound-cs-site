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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="vendor/mdbootstrap/css/mdb.min.css">

    <link rel="stylesheet" href="assets/style.css?t=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='shortcut icon' href='https://utopiafps.pl/uploads/monthly_2021_12/utopiafps-favicon.png' type="image/png">
</head>

<body>
    <div class="container">
        <button class="btn btn-info mt-3"><i class="fas fa-check-double"></i> Zweryfikowanych plików: <strong><?php echo $class->getFilesCount(); ?></strong></button>
        <?php if ($random) : ?>
            <a href="download.php?random=5" class="btn btn-secondary">
                <i class="fas fa-random"></i>
                <span>Pobierz losowe</span>
            </a>
        <?php endif; ?>
        <h1 class="mt-3">Tworzenie roundsound</h1>
        <p>Dodaj lub przeciągnij pliki <strong>MP3</strong> i określ ich nazwę i długość. <br />Po dodaniu i ustawieniu piosenek, kliknij <strong>Rozpocznij tworzenie</strong> i poczekaj na wygenerowanie paczki.</p>
        <ul class="nav nav-tabs mb-3" id="tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="files-tab-1" data-mdb-toggle="tab" href="#files-tab" role="tab" aria-controls="files-tab" aria-selected="true">Pliki</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="youtube-tab-1" data-mdb-toggle="tab" href="#youtube-tab" role="tab" aria-controls="youtube-tab" aria-selected="false">YouTube</a>
            </li>
        </ul>
        <!-- Tabs navs -->
        <div class="tab-content" id="files-tab-content">
            <div class="tab-pane fade show active" id="files-tab" role="tabpanel" aria-labelledby="files-tab">

                <div id="actions" class="row">
                    <div class="col-lg-7">
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
                        <input id="pack_id" type="hidden" name="pack_id" value="<?php echo $pack_id; ?>">
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
            </div>
            <div class="tab-pane fade" id="youtube-tab" role="tabpanel" aria-labelledby="youtube-tab">
                <div id="actions" class="row">
                    <div class="col-lg-7">
                        <button id="youtube-start-generate" type="button" class="btn btn-primary start">
                            <i class="far fa-play-circle"></i>
                            <span>Rozpocznij tworzenie</span>
                        </button>
                    </div>
                </div>

                <form id="youtube-form">
                    <p id="youtube-info" class="text-danger" style="display: none;">Rozpoczęto tworzenie paczki z YouTube, może to troche zająć...</p>
                    <div id="input-group-youtube" class="input-group input-group-youtube">
                        <span class="input-group-text">Link Youtube</span>
                        <input id="youtube-url-1" name="youtube-url[]" type="url" placeholder="https://www.youtube.com/watch?v=09ZctbeGcDA" class="form-control" />
                        <span class="input-group-text">Tytuł</span>
                        <input id="youtube-title-1" name="youtube-title[]" type="text" placeholder="Piosenka taka i taka" class="form-control" />
                        <span class="input-group-text">Czas Od:Czas Do</span>
                        <input id="youtube-time-1" name="youtube-time[]" type="text" placeholder="00:15-00:30" value="00:15-00:30" class="form-control" />
                        <button id="youtube-add-more" type="button" class="btn btn-primary"><i class="fas fa-plus-square"></i> Dodaj więcej</button>
                    </div>
                    <input id="pack_id" type="hidden" name="pack_id" value="<?php echo $pack_id; ?>">
                </form>

                <div id="input-group-youtube-copy" class="input-group input-group-youtube" style="display:none;">
                    <span class="input-group-text">Link YouTube</span>
                    <input id="youtube-url-1" name="youtube-url[]" type="url" placeholder="https://www.youtube.com/watch?v=09ZctbeGcDA" class="form-control" />
                    <span class="input-group-text">Tytuł</span>
                    <input id="youtube-title-1" name="youtube-title[]" type="text" placeholder="Piosenka taka i taka" class="form-control" />
                    <span class="input-group-text">Czas Od:Czas Do</span>
                    <input id="youtube-time-1" name="youtube-time[]" type="text" placeholder="00:15-00:30" value="00:15-00:30" class="form-control" />
                    <button id="youtube-remove-more" class="btn btn-danger"><i class="fas fa-minus-square"></i> Usuń to pole</button>
                </div>
            </div>
        </div>

        <a id="download" style="display: none;" href="download.php?pack_id=<?php echo $pack_id; ?>" class="btn btn-primary">Pobieranie paczki</a>
    </div>
    <footer class="mt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Stworzone przez <strong>DaFFyy</strong> z dużą ilością <i style="color: orange;" class="fas fa-cookie"></i> dla <a href="https://roundsound.cs.daffyy.pl">roundsound.cs.daffyy.pl</a> &copy;<?php echo date('Y'); ?> </p>
                </div>
            </div>
        </div>
    </footer>


    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="vendor/mdbootstrap/js/mdb.min.js"></script>
    <script src="vendor/dropzonejs/dropzone.min.js"></script>
    <script src="assets/main.js?t=<?php echo time(); ?>"></script>
</body>

</html>