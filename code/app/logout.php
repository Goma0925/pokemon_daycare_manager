
<?php   
        include_once 'utils/Settings.php';
        Settings::setup_debug(); //Custom function that contains debug settings
        include_once 'views/CommonView.php';
        include_once 'views/AuthView.php';
        include_once 'controllers/AuthContr.php';
        $authContr = new AuthContr();
        $authContr->logout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
        $commonView = new CommonView();
        $commonView->header("Logout");
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
    ?>
    <div style="margin-left:5%; margin-right:5%; margin-top: 25px;">
    <?php 
        $authView = new AuthView();
        $authView -> logoutMessage();
    ?>
    </div>
</body>
</html>