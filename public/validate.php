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
$config = yaml_parse_file("/flurga/config.yml");
$frigateIP = $config['frigate']['host'];
$frigateHost = "http://" . $frigateIP . "/api";

if(isset($config['flurga']['lang']) && file_exists("lang/" . $config['flurga']['lang'] . ".php")){
    include "lang/".$config['flurga']['lang'].".php";
} else {
    include "lang/en.php";
}

if (@date_default_timezone_set($config['flurga']['timezone']) == FALSE) {
    $err = TZ_ERROR;
}

$fh = @file_get_contents($frigateHost);
if ($fh != "Frigate is running. Alive and healthy!") {
    $err = FRIGATEIP_ERROR;
}

$i = 0;
while ($config['frigate']['cameras'][$i] ?? null) {
    if (get_headers($frigateHost . "/" . $config['frigate']['cameras'][$i] . "/latest.jpg", 1)[0] != "HTTP/1.1 200 OK") {
        $err = CAM_ERROR . " (" . $config['frigate']['cameras'][$i] . ")";
    }
    $i++;
}

if (!isset($err)) {
    return;
}
?>
<html>

<head>
    <title>Flurga - Error</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="manifest" href="site.webmanifest" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/bootstrap-italia.min.css" />
    <script>
        window.__PUBLIC_PATH__ = 'webfonts/'
    </script>
</head>

<body>
    <div class="container-fluid bg-primary pt-2 pb-2">
        <div class="row">
            <div class="col-sm text-center">
                <h3 class="text-white">Flurga</h3>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center m-3">
            <div class="col-auto ps-4 pe-4 pt-3 rounded-3" style="background-color:#ebebeb">
                <?php
                echo ('<div class="alert alert-danger" style="background-color:white" role="alert"><b>ERROR</b><br>' . $err . '</div>');
                ?>
                <div class="row">
                    <input type="submit" class="btn btn-primary text-white mb-3" onClick="window.location.reload();" value="Refresh">
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap-italia.bundle.min.js"></script>
</body>

</html>
<?php
die;
?>