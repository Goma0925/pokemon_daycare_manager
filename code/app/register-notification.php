<!DOCTYPE html>
<html lang="en">
<head>
    <?php     
        include_once 'utils/Settings.php';
        Settings::setup_debug(); //Custom function that contains debug settings
        include_once 'views/CommonView.php';
        include_once 'views/NotificationView.php';
        include_once 'controllers/NotificationContr.php';
        include_once 'views/InputErrorView.php';
    ?>
    <?php   
        $commonView = new CommonView();
        $commonView->header("Notifications Search");
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%;margin-right:5%; margin-top: 25px;">
        <p>Adding Event</p>
        <p> 
            <form action="register-notification.php" method="GET">
                <input type="hidden" value="true" name="active">
                <input type="hidden" value="all" name="trainer">
                <button type="submit" style="margin-left: 10px; float:right" value="fight-update" name="addFight" type="button" class="btn btn-secondary">Report Fight</button>
                <button type="submit" style="margin-left: 10px;float:right" value="egg-update" name="addEgg" type="button" class="btn btn-secondary">Report Egg</button>
                <button type="submit" style="margin-left: 10px;float:right" value="move-update" name="addMove" type="button" class="btn btn-secondary">Report New Move</button>
            </form>
        </p>


    <?php 
        
        // Render table
        $notificationView = new NotificationView();
        $inputErrorView = new InputErrorView();
        $notificationContr = new NotificationContr();

 



        if (isset($_GET["addEgg"])){
            if (isset($_POST["parent1"])) {
                $resultContainer = $notificationContr->addEggEvent($_POST["eventdatetime"], $_POST["parent1"], $_POST["parent2"]);
                if ($resultContainer->isSuccess()){
                    $notificationView->registrationSuccessMessage();
                }else{
                    $errorMessages = $resultContainer->getErrorMessages();
                    $inputErrorView->errorBox($errorMessages);
                }

                
            }
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name. It ignores the difference between lowercase and uppercase.
            $notificationView -> eggeventRegistrationForm();
        }
        if (isset($_GET["addMove"])){



            
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name. It ignores the difference between lowercase and uppercase.
            $notificationView -> moveeventRegistrationForm();
        }

        if (isset($_GET["chooseMove"])){

            if (isset($_POST["currentMoves"])) {
                $resultContainer = $notificationContr->addMoveEvent($_GET["eventdatetime"], $_GET["pokemon"], $_POST["currentMoves"], $_POST["move-1"]);
                if ($resultContainer->isSuccess()){
                    $notificationView->registrationSuccessMessage();
                    
                    
                }else{
                    $errorMessages = $resultContainer->getErrorMessages();
                    $inputErrorView->errorBox($errorMessages);
                }


                
            }

            $notificationView -> changeMoves($_GET["pokemon"]);


        }



        if (isset($_GET["addFight"])){
            if (isset($_POST["pokemon"])) {
                $resultContainer = $notificationContr->addFightEvent( $_POST["pokemon"], $_POST["description"],$_POST["eventdatetime"]);
                if ($resultContainer->isSuccess()){
                    $notificationView->registrationSuccessMessage();
                }else{
                    $errorMessages = $resultContainer->getErrorMessages();
                    $inputErrorView->errorBox($errorMessages);
                }

                
            }
            
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name. It ignores the difference between lowercase and uppercase.
            $notificationView -> fighteventRegistrationForm();
        }
        
    ?>
    </div>
</body>
</html>