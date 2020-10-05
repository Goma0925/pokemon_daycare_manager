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
        <br>
        <h1>Trainer Search</h1>
        <form method="post">
            <p>Search trainers: 
                <input type="text" name="trainer-query">
                <input type="submit" value="Search">
                <a style="float:right" href="./trainer_registration.php" type="button" class="btn btn-secondary">Register new trainer</a>
            </p>
        </form>
    <?php 
        // Render table
        $trainerView = new TrainersView();
        if (isset($_POST["trainer-query"])){
            $name = $_POST["trainer-query"];
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name. It ignores the difference between lowercase and uppercase.
            $trainerView->trainerSelectionTableByName($name, "pokemon_selector.php");
        }

    ?>
    </div>
</body>
</html>