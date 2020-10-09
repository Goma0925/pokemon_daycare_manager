<!DOCTYPE html>
<html lang="en">
<head>
    <?php     
        include_once 'utils/Settings.php';
        Settings::setup_debug(); //Custom function that contains debug settings
        include_once 'views/CommonView.php';
        include_once 'views/InputErrorView.php';
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
<div class="card text-center">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
        <?php 
            //Tab display switch
                if (!isset($_GET["redirect-to"]) || $_GET["redirect-to"]=="check-in-pokemon"){
                    echo '
                    <li class="nav-item">
                        <a class="nav-link active" href="check-in-and-out.php?redirect-to=check-in-pokemon">Check-In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="check-in-and-out.php?redirect-to=check-out-pokemon">Check-Out</a>
                    </li>
                    ';
                }else{
                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="check-in-and-out.php?redirect-to=check-in-pokemon">Check-In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="check-in-and-out.php?redirect-to=check-out-pokemon">Check-Out</a>
                    </li>
                    ';
                }
        ?>
        </ul>
    </div>
    <div class="card-body">
        <div style="margin-left:5%;margin-right:5%; margin-top: 25px;">
        <p>Temporary message: Hello guys! In the database, there are trainers named 'Satoshi', 'Jake', 'John'. Try looking them up!</p>
        <h2>
            <?php 
                if (!isset($_GET["redirect-to"]) || $_GET["redirect-to"]=="check-in-pokemon"){
                    echo "Search for a customer to check-in";
                }else if ($_GET["redirect-to"]=="check-out-pokemon"){
                    echo "Search for a customer to check-out";
                }
            ?>
        </h2>
        <form method="GET">
            <p>Search trainers by name: 
                <?php 
                    //Change the search query action by check-in or out
                    if (!isset($_GET["redirect-to"]) || $_GET["redirect-to"]=="check-in-pokemon"){
                        echo '
                            <input type="hidden" name="redirect-to" value="check-in-pokemon">
                        ';
                    }else{
                        echo '
                            <input type="hidden" name="redirect-to" value="check-out-pokemon">
                        ';
                    }
                ?>
                <input type="text" name="trainer-name" style="width:50%;">
                <input type="submit" value="Search">
                <a style="float:right" href="./register-trainer.php" type="button" class="btn btn-secondary">Register new trainer</a>
            </p>
        </form>
        <?php 
            $trainersView = new TrainersView();
            $inputErrorView = new InputErrorView();
            if (!isset($_GET["redirect-to"]) || $_GET["redirect-to"]=="check-in-pokemon"){
                if (isset($_GET["trainer-name"])){
                    $trainer_name = $_GET["trainer-name"];
        
                    //Define where to direct the request of trainer selection table's form.
                    // switch $_GET["redirect-to"];
                    //     case
                    //     break;
                    $action = "select-pokemon.php";
        
                    // Define the method by which the trainer selection table sends its request.
                    $method = "GET";
        
                    //Define form names and values to set in the selection table form.
                    $form_params = Array(
                        "redirect-to"=>"check-in-pokemon", //This is used to route pages in select-pokemon.php
                        "active"=> "false" //Request to show the inactive pokemon in select-pokemon.php
                    );
                    //Render trainer table. This function matches all the trainers whose name contains partial/entire
                    //string of the $name and renders a table in a HTML form, from which you can select a single trainer.
                    //For the string name search, it ignores the difference between lowercase and uppercase.
                    $trainersView->trainerSelectionTableByName($trainer_name, "Check-in", $action, $method, $form_params);
                }
            }else{
                if (isset($_GET["trainer-name"])){
                    $trainer_name = $_GET["trainer-name"];
        
                    //Define where to direct the request of trainer selection table's form.
                    // switch $_GET["redirect-to"];
                    //     case
                    //     break;
                    $action = "select-pokemon.php";
        
                    // Define the method by which the trainer selection table sends its request.
                    $method = "GET";
        
                    //Define form names and values to set in the selection table form.
                    $form_params = Array(
                        "redirect-to"=>"check-out-pokemon", //This is used to route pages in select-pokemon.php
                        "active"=> "true" //Request to show the active pokemon in select-pokemon.php
                    );
                    //Render trainer table. This function matches all the trainers whose name contains partial/entire
                    //string of the $name and renders a table in a HTML form, from which you can select a single trainer.
                    //For the string name search, it ignores the difference between lowercase and uppercase.
                    $trainersView->trainerSelectionTableByName($trainer_name, "Check-out", $action, $method, $form_params);
                }  
            }

        ?>
        </div>
    </div>
</div>


</body>
</html>