<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/InputErrorView.php';
            include_once 'views/ServiceRecordsView.php';
            include_once 'controllers/ServiceRecordsContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        $commonView->header("Check-in Confirmation"); 
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%; margin-right:5%; margin-top: 25px;">
    <?php 
        $serviceRecordsView = new ServiceRecordsView();
        $serviceRecordsContr = new ServiceRecordsContr();
        $inputErrorView = new InputErrorView();

        if (isset($_GET["trainer"]) && isset($_GET["pokemon"])){
            $action = "add-service-record.php";
            $method = "POST";
            $form_params = Array();
            $serviceRecordsView->checkInConfirmationBox($_GET["trainer"], $_GET["pokemon"], $action, $method, $form_params);
        }
        // After the user clicks Check In button, it'll send a post to insert a service record.
        else if (isset($_POST["trainer"]) && isset($_POST["pokemon"])){
            $resultContainer = $serviceRecordsContr->addServiceRecord($start_time = null,  $pokemon_id=$_POST["pokemon"], $trainer_id=$_POST["trainer"]);
            if ($resultContainer->isSuccess()){
                $serviceRecordsView->checkInCompletionBox($_POST["trainer"], $_POST["trainer_name"], $_POST["pokemon_nickname"]);
            }else{
                $errorMessages = $resultContainer->getErrorMessages();
                $inputErrorView->errorBox($errorMessages);
            }
        }else{
            $errorMessages = Array("Invalid request. Please try again from the beginning.");
            $inputErrorView->errorBox($errorMessages);
        }
    ?>
    </div>
</body>
</html>