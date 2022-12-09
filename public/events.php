<?php
$config = parse_ini_file("../app.ini", true);
$frigateIP = $config['config']['ip'];

if (isset($_POST['del'])) {
    $ch = curl_init('http://' . $frigateIP . '/api/events/' . $_POST['del']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    header("Location: " . $_SERVER['PHP_SELF']);
}
$json = file_get_contents("http://" . $frigateIP . "/api/events");
$data = json_decode($json);

$i = 0;
$j = 0;
?>
<html>

<head>
    <title>Flurga - Events</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-italia@2.0.9/dist/css/bootstrap-italia.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</head>

<body>
    <div class="container-fluid bg-primary p-2">
        <div class="row">
            <div class="col-sm text-center">
                <a href="index.php">
                    <h3 class="text-white">Flurga</h3>
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid primary-bg-b2 p-2 shadow ">
        <div class="row">
            <div class="col-sm text-center">
                <a href="index.php" class="text-white text-decoration-none me-1">Homepage</a>
                <a href="recordings.php" class="text-white text-decoration-none ms-1">Recordings</a>
            </div>
        </div>
    </div>

    <?php
    while ($j != 3 && ($data[$i] ?? null)) {
        echo ('<div class="row justify-content-center" style="padding-top:25px;margin: auto">');
        while (($data[$i] ?? null) and ($j < 3)) {
            $link = 'http://' . $frigateIP . '/api/events/' . $data[$i]->id . '/thumbnail.jpg';
            $linkSnap = 'http://' . $frigateIP . '/api/events/' . $data[$i]->id . '/snapshot.jpg';
            $linkClip = 'http://' . $frigateIP . '/api/events/' . $data[$i]->id . '/clip.mp4';

            echo ('<div class="col-lg-3">');
            echo ('<div class="card-wrapper">');
            echo ('<div class="card card-bg no-after">');
            echo ('<a href="' . $linkSnap . '" target="_blank"><img src="' . $linkSnap . '" width="100%" class="img-fluid" /></a>');
            echo ('<div class="card-body">');
            echo ('<h3 class="card-title h5 ">' . $data[$i]->camera . " (" . ucfirst($data[$i]->label) . " - " . round($data[$i]->top_score * 100) . '%)<p class="card-text">' . date('m/d/Y H:i:s', $data[$i]->start_time) . '</p></h3>');
            echo ('<p class="card-text"><a href="' . $linkClip . '" target="_blank">View the clip</a></p>');
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
</body>
<script src="https://kit.fontawesome.com/f26c5ea5b1.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-italia@2.0.9/dist/js/bootstrap-italia.bundle.min.js"></script>
<script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>

</html>