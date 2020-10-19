
<?php   
        include_once 'utils/Settings.php';
        Settings::setup_debug(); //Custom function that contains debug settings
        include_once 'views/CommonView.php';
        include_once 'views/AuthView.php';
        include_once 'controllers/AuthContr.php';
?>
<?php 
    $authContr = new AuthContr();
    if (isset($_POST["username"]) && isset($_POST["password"])){
        $logged_in = false;
        $authContr->login($_POST["username"], $_POST["password"]);
        if ($_SESSION["authenticated"]){
            //Jump to the main page
            header("Location: check-in-and-out.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
        $commonView = new CommonView();
        // Replace the page title here. It generates title and CSS & JavaScript links.
        $commonView->header("Login"); 
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%; margin-right:5%; margin-top: 25px;">
    <?php 
        $authView = new AuthView();
        $authView -> loginBox("login.php");
    ?>
    </div>
</body>
</html>