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

$json = file_get_contents("http://" . $frigateIP . "/api/events");
$data = json_decode($json);

if (isset($_POST['del'])) {
    $ch = curl_init('http://' . $frigateIP . '/api/events/' . $_POST['del']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    header("Location: " . $_SERVER['PHP_SELF']);
}
if (isset($_POST['dall'])) {
    $i = 0;
    do {
        while ($data[$i] ?? null) {
            $ch = curl_init('http://' . $frigateIP . '/api/events/' . $data[$i]->id);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            $i++;
        }
        $json = file_get_contents("http://" . $frigateIP . "/api/events");
        $data = json_decode($json);
        $i = 0;
    } while ($data[$i] ?? null);
    header("Location: " . $_SERVER['PHP_SELF']);
}
?>
<html>

<head>
    <title>Flurga - <?= EVENTS ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
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
                <a href="/" class="text-white text-decoration-none me-1"><?= HOMEPAGE ?></a>
                <a href="recordings" class="text-white text-decoration-none ms-1"><?= RECORDINGS ?></a>
                <a href="logout" class="text-white text-decoration-none ms-4"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-auto">
                <div class="form-group mt-3 mb-0">
                    <form class="mb-0" method="post"><button type="submit" name="dall" formmethod="post" class="btn btn-primary" style="width:100%"><?= DELETE_ALL_EVENTS ?></button></form>
                </div>
            </div>
        </div>
    </div>

    <?php
    $i = 0;
    $j = 0;
    if (!isset($data[$i])) {
        echo ("<div class='row justify-content-center'><div class='col-auto'><div class='alert alert-danger mt-3' style='background-color:white' role='alert'>No events found!</div></div></div>");
    }
    while ($j != 3 && ($data[$i] ?? null)) {
        echo ('<div class="row justify-content-center" style="padding-top:25px;margin: auto">');
        while (($data[$i] ?? null) and ($j < 3)) {
            $link = 'http://' . $frigateIP . '/api/events/' . $data[$i]->id . '/thumbnail.jpg';
            $linkSnap = 'http://' . $frigateIP . '/api/events/' . $data[$i]->id . '/snapshot.jpg';
            $linkClip = 'http://' . $frigateIP . '/api/events/' . $data[$i]->id . '/clip.mp4';

            echo ('<div class="col-lg-3">');
            echo ('<div class="card-wrapper">');
            echo ('<div class="card card-bg no-after">');

            $ch = curl_init($linkSnap);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $respcod = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($respcod != 200) {
                echo ('<div class="alert alert-danger m-4" style="background-color:white" role="alert">Image not found!</div>');
            } else {
                echo ('<a href="' . $linkSnap . '" target="_blank"><img src="' . $linkSnap . '" width="100%" class="img-fluid" /></a>');
            }

            echo ('<div class="card-body">');
            echo ('<h3 class="card-title h5 ">' . $data[$i]->camera . " (" . ucfirst($data[$i]->label) . " - " . round($data[$i]->top_score * 100) . '%)<p class="card-text">' . date('d/m/Y H:i:s', $data[$i]->start_time) . '</p></h3>');
            echo ('<p class="card-text"><a href="' . $linkClip . '" target="_blank">' . VIEW_CLIP . '</a></p>');
            echo ('<form style="margin-bottom:0px"><button class="btn btn-primary" type="submit" formmethod="post" name="del" value="' . $data[$i]->id . '"><i class="fa-regular fa-trash-can"></i></button></form>');
            echo ('</div>');
            echo ('</div>');
            echo ('</div>');
            echo ('</div>');

            $i++;
            $j++;
        }
        echo ('</div>');
        $j = 0;
    }
    ?>
    <br>

    <script src="js/bootstrap-italia.bundle.min.js"></script>
</body>

</html>