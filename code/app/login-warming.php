<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/AuthView.php';
    ?>
    <?php   
        $commonView = new CommonView();
        $commonView->header("Login Warming"); 
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
        $authView->loginWarning();
    ?>
    </div>
</body>
</html>