<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index");
    exit;
}

$config = parse_ini_file("/flurga/app.ini", true);
date_default_timezone_set($config['config']['tz']);

if(isset($_POST['submit'])){
    if($_POST["password"] == $config['config']['password']){
        session_start();
        
        $_SESSION["loggedin"] = true;                            
        
        header("location: index");
    } else{
        $login_err = "Invalid password!";
    }
}

?>
<html>

<head>
    <title>Flurga</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-italia@2.0.9/dist/css/bootstrap-italia.min.css" />
</head>

<body style="background-color:#404040">

    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-auto ps-4 pe-4 pt-3 rounded-3" style="background-color:#ebebeb">
                <?php
                if(!empty($login_err)){
                    echo '<div class="alert alert-danger" style="background-color:white" role="alert">' . $login_err . '</div>';
                }
                ?>
                <h1 align="center" style="color:#0066cc">Flurga</h1>
                <form method="post">
                    <div class="form-group mt-5 mb-4">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" aria-describedby="passwordDescription">
                        <small id="passwordDescription" class="form-text">Please fill in your password to login</small>
                    </div>
                    <div class="form-group mb-3 text-end">
                        <input type="submit" name="submit" class="btn btn-primary text-white" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/bootstrap-italia@2.0.9/dist/js/bootstrap-italia.bundle.min.js"></script>
</body>

</html>