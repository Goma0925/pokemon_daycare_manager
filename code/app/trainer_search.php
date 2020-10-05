<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
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
        <h1>Trainer Search</h1>
        <p>Temporary message: Hello guys! In the database, there are trainers named 'Satoshi', 'Jake', 'John'. Try looking them up!</p>
        <p><a href="./trainer_registration.php" type="button" class="btn btn-secondary">Register new trainer</a></p>
    <?php 
        echo '
            <br>
                <form method="post">
                    <p>Search trainers: 
                        <input type="text" name="trainer-query">
                        <input type="submit" value="Search">
                    </p>
                </form>
        ';  

        // Render table
        $trainerView = new TrainersView();
        if (isset($_POST["trainer-query"])){
            $name = $_POST["trainer-query"];
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name. It ignores the difference between lowercase and uppercase.
            $trainerView->trainerTableByName($name, "pokemon_table.php");
        }

    ?>
    </div>
</body>
</html>