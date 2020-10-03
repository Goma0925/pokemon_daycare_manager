<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Registration</title>
    <?php   ini_set('display_errors', 1);
            ini_set("display_startup_errors", 1);
            error_reporting(E_ALL);
    ?>
    <!-- Added Bootstrap CSS just because it's more fun :) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>   
    <?php   
            include 'views/CommonView.php';
            include 'views/TrainersView.php';
            include 'views/InputErrorView.php';
            include 'controllers/TrainersContr.php';?>
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
                $trainerView->registrationSuccessMessage();
            }else{
                $errorMessages = $resultContainer->getErrorMessages();
                $inputErrorView->errorBox($errorMessages);
            }
        }

        //Render the registration form
        $trainerView->trainerRegistrationForm();
    ?>
    </div>
</body>
</html>