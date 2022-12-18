<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login");
    exit;
}

$config = parse_ini_file("/flurga/app.ini", true);
$frigateIP = $config['config']['ip'];
date_default_timezone_set($config['config']['tz']);
?>
<html>

<head>
    <title>Flurga - Recordings</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-italia@2.0.9/dist/css/bootstrap-italia.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
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
                <a href="/" class="text-white text-decoration-none me-1">Homepage</a>
                <a href="events" class="text-white text-decoration-none ms-1">Events</a>
                <a href="logout" class="text-white text-decoration-none ms-4"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>
    </div>

    <?php
    if (isset($_GET['ti'], $_GET['tf'], $_GET['cam'])) {
        $timestampI = $_GET['ti'];
        $timestampF = $_GET['tf'];
        $cam = $_GET['cam'];
        $link = 'http://' . $frigateIP . '/vod/' . $cam . '/start/' . $timestampI . '/end/' . $timestampF . '/index.m3u8';
        $downLink = 'http://' . $frigateIP . '/api/' . $cam . '/start/' . $timestampI . '/end/' . $timestampF . '/clip.mp4';
        echo ('<div class="container" style="width:100%;height:50%;padding-bottom:25px;padding-top:25px">');
        echo ('<video id="my_video_1" class="video-js" controls preload="auto" style="width:100%;height:100%" data-setup="{}">');
        echo ('<source src="' . $link . '" type="application/x-mpegURL">');
        echo ('</video>');
        echo ('<a href="' . $downLink . '" target="_blank" download="a.mp4">Download video</a>');
        echo ('</div>');
    }
    ?>

    <div class="container-fluid p-4">
        <?php
        $k = 0;
        while ($config['config']['cameras'][$k] ?? null) {
            $i = 0;
            $json = file_get_contents("http://" . $frigateIP . "/api/" . $config['config']['cameras'][$k] . "/recordings/summary");
            $data = json_decode($json);
            echo ('<div class="link-list-wrapper d-flex justify-content-center ">');
            echo ('<ul class="link-list primary-bg">');
            echo ('<li class="p-3">');
            echo ('<a class="list-item large medium" href="#' . $config['config']['cameras'][$k] . '" data-bs-toggle="collapse" aria-expanded="false" aria-controls="' . $config['config']['cameras'][$k] . '">');
            echo ('<span class="list-item-title-icon-wrapper">');
            echo ('<span class="list-item-title text-white">' . $config['config']['cameras'][$k] . '</span>');
            echo ('<svg class="icon icon-primary"><i class="fa-solid fa-angle-down text-white"></i></svg>');
            echo ('</span>');
            echo ('</a>');
            while ($data[$i]->day ?? null) {
                echo ('<ul class="link-sublist collapse" id="' . $config['config']['cameras'][$k] . '">');
                echo ('<li>');
                echo ('<a class="list-item" href="#' . $config['config']['cameras'][$k] . $i . '" data-bs-toggle="collapse" aria-expanded="false" aria-controls="' . $config['config']['cameras'][$k] . $i . '">');
                echo ('<span class="list-item-title-icon-wrapper">');
                echo ('<span class="list-item-title text-white">' . date("d/m/Y", strtotime($data[$i]->day)) . '</span>');
                echo ('<svg class="icon icon-primary"><i class="fa-solid fa-angle-down text-white"></i></svg>');
                echo ('</span>');
                echo ('</a>');
                echo ('<ul class="link-sublist collapse" id="' . $config['config']['cameras'][$k] . $i . '">');
                $j = count($data[$i]->hours) - 1;
                while ($data[$i]->hours[$j]->hour ?? null) {
                    $oraI = $data[$i]->hours[$j]->hour . ":00";
                    $oraF = sprintf("%02d", ($data[$i]->hours[$j]->hour) + 1) . ":00";
                    $dataInizio = $data[$i]->day . " " . $oraI;
                    $dataFine = $data[$i]->day . " " . $oraF;
                    $timestampI = \DateTime::createFromFormat('Y-m-d H:i', $dataInizio)->getTimestamp();
                    $timestampF = \DateTime::createFromFormat('Y-m-d H:i', $dataFine)->getTimestamp();
                    $link = 'http://' . $frigateIP . '/' . $config['config']['cameras'][$k] . '/start/' . $timestampI . '/end/' . $timestampF . '/clip.mp4';
                    $ilink = '?ti=' . $timestampI . '&tf=' . $timestampF . '&cam=' . $config['config']['cameras'][$k];
                    echo ('<li><a class="list-item" href="' . $ilink . '"><span class="text-white">' . $oraI . ' - ' . $oraF . '</span></a></li>');
                    $j--;
                }
                echo ('</ul>');
                echo ('</li>');
                echo ('</ul>');
                $i++;
            }
            echo ('</li>');
            echo ('</ul>');
            echo ('</div>');
            $k++;
        }
        ?>
    </div>

    <script src="https://kit.fontawesome.com/f26c5ea5b1.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-italia@2.0.9/dist/js/bootstrap-italia.bundle.min.js"></script>
    <script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>
</body>

</html>