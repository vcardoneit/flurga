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

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index");
    exit;
}

include 'validate.php';

if (isset($_POST['submit'])) {
    if ($_POST["username"] == $config['flurga']['username']) {
        if ($_POST["password"] == $config['flurga']['password']) {
            session_start();
            $_SESSION["loggedin"] = true;
            header("location: index");
        } else {
            $login_err = INVALID_PASSWORD;
        }
    } else {
        $login_err = INVALID_USERNAME;
    }
}
?>
<html>

<head>
    <title>Flurga</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/bootstrap-italia.min.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <script>
        window.__PUBLIC_PATH__ = 'webfonts/'
    </script>
</head>

<body style="background-color:#404040">

    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-auto ps-4 pe-4 pt-3 rounded-3" style="background-color:#ebebeb">
                <?php
                if (!empty($login_err)) {
                    echo '<div class="alert alert-danger" style="background-color:white" role="alert">' . $login_err . '</div>';
                }
                ?>
                <h1 align="center" style="color:#0066cc">Flurga</h1>
                <form method="post">
                    <div class="form-group mt-5" style="margin-bottom:40px;">
                        <label for="username"><?= USERNAME ?></label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="<?= USERNAME ?>" aria-describedby="usernameDescription">
                        <small id="usernameDescription" class="form-text"><?= UN_FILL ?></small>
                    </div>
                    <div class="form-group mb-4">
                        <label for="password"><?= PASSWORD ?></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="<?= PASSWORD ?>" aria-describedby="passwordDescription">
                        <small id="passwordDescription" class="form-text"><?= PW_FILL ?></small>
                    </div>
                    <div class="form-group mb-3 text-end">
                        <input type="submit" name="submit" class="btn btn-primary text-white" value="<?= LOGIN ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/bootstrap-italia.bundle.min.js"></script>
</body>

</html>