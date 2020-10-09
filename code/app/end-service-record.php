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

        // On GET request, render check-out confirmation box.
        if (isset($_GET["service"])){
            $service_record_id = $_GET["service"];
            $action = "end-service-record.php";
            $method = "POST";
            $form_params = Array();
            $resultContainer = $serviceRecordsView->checkOutConfirmationBox($service_record_id, $action, $method, $form_params);
            //When given invalid trainer or pokemon ID in GET params, show error messages.
            if ($resultContainer->isSuccess()){
                $errorMessages = $resultContainer->getErrorMessages();
                $inputErrorView->errorBox($errorMessages);
            }
        }

        // After the user clicks Check out button, it'll send a post to insert the end time in the service record.
        else if (isset($_POST["service"])){
            $service_record_id = $_POST["service"];
            $resultContainer = $serviceRecordsContr->endServiceRecord($service_record_id);
            if ($resultContainer->isSuccess()){
                $serviceRecordsView->checkOutCompletionBox();
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