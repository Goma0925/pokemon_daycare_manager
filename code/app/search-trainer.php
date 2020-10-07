<!DOCTYPE html>
<html lang="en">
<head>
    <?php     
        include_once 'utils/Settings.php';
        Settings::setup_debug(); //Custom function that contains debug settings
        include_once 'views/CommonView.php';
        include_once 'views/TrainersView.php';
        include_once 'controllers/TrainersContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        $commonView->header("Trainer Search");
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%;margin-right:5%; margin-top: 25px;">
        <p>Temporary message: Hello guys! In the database, there are trainers named 'Satoshi', 'Jake', 'John'. Try looking them up!</p>
        <h2>Trainer Search</h2>
        <form method="GET">
            <p>Search trainers by name: 
                <input type="text" name="trainer-name">
                <input type="submit" value="Search">
                <a style="float:right" href="./register-trainer.php" type="button" class="btn btn-secondary">Register new trainer</a>
            </p>
        </form>
    <?php 
        // Render table
        $trainerView = new TrainersView();
        if (isset($_GET["trainer-name"])){
            $name = $_GET["trainer-name"];

            //Define where to direct the request of trainer selection table's form.
            $action = "select-pokemon.php";

            // Define the method by which the trainer selection table sends its request.
            $method = "GET";

            //Define form names and values to set in the selection table form.
            $form_params = Array(
                "redirect-to"=>"check-in-confirmation", //This is used to route pages in select-pokemon.php
            );
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name and renders a table in a HTML form, from which you can select a single trainer.
            //For the string name search, it ignores the difference between lowercase and uppercase.
            $trainerView->trainerSelectionTableByName($name, $action, $method, $form_params);
        }

    ?>
    </div>
</body>
</html>