<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC demo</title>
    <?php   ini_set('display_errors', 1);
            ini_set("display_startup_errors", 1);
            error_reporting(E_ALL);
    ?>
    <!-- Added Bootstrap CSS just because it's more fun :) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>   

    <?php   include_once 'views/CommonView.php';
            include_once 'views/TrainersView.php';
            include_once 'controllers/TrainersContr.php';?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%;margin-right:5%; margin-top: 25px;">
        <h1>Trainer Search</h1>
        <p>Temporary message: Hello guys! In the database, there are trainers named 'Satoshi', 'Jake', 'John'. Try looking them up!</p>
        <p><a href="./trainer-registration.php" type="button" class="btn btn-secondary">Register new trainer</a></p>
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
            $trainerView->trainerSearchTableByName($name);
        }

    ?>
    </div>
</body>
</html>