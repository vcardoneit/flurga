<?php
/* 
   Copyright (C) 2022  Vincenzo Cardone <vnc@vcardone.it>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login");
    exit;
}

include 'validate.php';
?>
<html>

<head>
    <title>Flurga</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/video-js.css" />
    <link rel="stylesheet" href="css/bootstrap-italia.min.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <script>
        window.__PUBLIC_PATH__ = 'webfonts/'
    </script>
</head>

<body>

    <div class="container-fluid bg-primary pt-2 pb-2">
        <div class="row">
            <div class="col-sm text-center">
                <a href="/">
                    <h3 class="text-white">Flurga</h3>
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid primary-bg-b2 pt-2 pb-2 shadow">
        <div class="row">
            <div class="col-sm text-center">
                <a href="events" class="text-white text-decoration-none me-1">Events</a>
                <a href="recordings" class="text-white text-decoration-none ms-1">Recordings</a>
                <a href="logout" class="text-white text-decoration-none ms-4"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>
    </div>

    <div class="container">
        <form method="post">
            <div class="row justify-content-center align-items-center" style="margin-top:40px">
                <div class="form-group col-md-3">
                    <label class="active" for="giorno">Date</label>
                    <input type="date" id="giorno" name="giorno">
                </div>
                <div class="form-group col-md-3 text-center" style="margin-bottom:0px">
                    <?php
                    $i = 0;
                    while ($config['frigate']['cameras'][$i] ?? null) {
                        echo ('<div class="form-check form-check-inline">');
                        echo ('<input name="CAM" type="radio" id="' . $config['frigate']['cameras'][$i] . '" value="' . $config['frigate']['cameras'][$i] . '" required>');
                        echo ('<label for="' . $config['frigate']['cameras'][$i] . '">' . $config['frigate']['cameras'][$i] . '</label>');
                        echo ('</div>');
                        $i++;
                    }
                    ?>
                </div>
            </div>
            <div class="row justify-content-center align-items-center">
                <div class="form-group col-md-3">
                    <label class="active" for="oraInizio">Start time</label>
                    <input class="form-control" id="oraInizio" name="oraInizio" type="time" required>
                </div>
                <div class="form-group col-md-3">
                    <label class="active" for="oraFine">End time</label>
                    <input class="form-control" id="oraFine" name="oraFine" type="time" required>
                </div>
            </div>
            <div class="row justify-content-center align-items-center">
                <div class="form-group col-md-3" style="margin-top:-25px">
                    <button type="submit" name="button" formmethod="post" class="btn btn-primary" style="width:100%">Search</button>
                </div>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['button'])) {
        $data = $_POST['giorno'];
        $oraI = $_POST['oraInizio'];
        $oraF = $_POST['oraFine'];
        $cam = $_POST['CAM'];
        $dataInizio = $data . " " . $oraI;
        $dataFine = $data . " " . $oraF;
        $timestampI = \DateTime::createFromFormat('Y-m-d H:i', $dataInizio)->getTimestamp();
        $timestampF = \DateTime::createFromFormat('Y-m-d H:i', $dataFine)->getTimestamp();
        $link = 'http://' . $frigateIP . '/vod/' . $cam . '/start/' . $timestampI . '/end/' . $timestampF . '/index.m3u8';
        $downLink = 'http://' . $frigateIP . '/api/' . $cam . '/start/' . $timestampI . '/end/' . $timestampF . '/clip.mp4';
        #echo ($timestampI . " " . $timestampF . " " . $link);
        echo ('<div class="container" style="width:100%;height:50%;padding-bottom:25px">');
        echo ('<video id="my_video_1" class="video-js" controls preload="auto" style="width:100%;height:100%" data-setup="{}">');
        echo ('<source src="' . $link . '" type="application/x-mpegURL">');
        echo ('</video>');
        echo ('<a href="' . $downLink . '" target="_blank" download="a.mp4">Download video</a>');
        echo ('</div>');
    }
    if (isset($_GET['ti'], $_GET['tf'], $_GET['cam'])) {
        $timestampI = $_GET['ti'];
        $timestampF = $_GET['tf'];
        $cam = $_GET['cam'];
        $link = 'http://' . $frigateIP . '/vod/' . $cam . '/start/' . $timestampI . '/end/' . $timestampF . '/index.m3u8';
        $downLink = 'http://' . $frigateIP . '/api/' . $cam . '/start/' . $timestampI . '/end/' . $timestampF . '/clip.mp4';
        echo ('<div class="container" style="width:100%;height:50%;padding-bottom:25px">');
        echo ('<video id="my_video_1" class="video-js" controls preload="auto" style="width:100%;height:100%" data-setup="{}">');
        echo ('<source src="' . $link . '" type="application/x-mpegURL">');
        echo ('</video>');
        echo ('<a href="' . $downLink . '" target="_blank" download="a.mp4">Download video</a>');
        echo ('</div>');
    }
    ?>
    <br>

    <script src="js/bootstrap-italia.bundle.min.js"></script>
    <script src="js/video.min.js"></script>
</body>

</html>