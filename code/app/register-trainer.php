<?php 
    session_start();
    //Ridirect to login warming to prompt login if not logged in.
    if ($_SESSION["authenticated"]==false){
        header("Location: login-warming.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/TrainersView.php';
            include_once 'views/InputErrorView.php';
            include_once 'controllers/TrainersContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        $commonView->header("Trainer Registration"); 
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%;margin-right:5%; margin-top: 25px;">
        <h1>Trainer Registration</h1>
    <?php
        $trainerView = new TrainersView();
        $inputErrorView = new InputErrorView();
        $trainersContr = new TrainersContr();

        // Add a new user if post is submitted.
        if (isset($_POST["name"])) {
            $resultContainer = $trainersContr->addTrainer($_POST["name"], $_POST["phone"], $_POST["email"]);
            if ($resultContainer->isSuccess()){
                $trainer_id = $resultContainer->getSuccessValues()["trainer_id"];
                $trainerView->registrationSuccessBox($trainer_id);
            }else{
                $errorMessages = $resultContainer->getErrorMessages();
                $inputErrorView->errorBox($errorMessages);
                //Render the registration form to retry
                $trainerView->trainerRegistrationForm();
            }
        }else{
            //Render the registration form
            $trainerView->trainerRegistrationForm();
        }


    ?>
    </div>
</body>
</html>