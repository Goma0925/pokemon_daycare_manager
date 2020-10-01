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
    <?php include 'views/TrainersView.php';?>
    <?php include 'controllers/TrainersContr.php';?>
</head>
<body>
    <div style="margin-left:5%;margin-right:5%">
        <p>Hello guys! In the database, there are trainers named 'Satoshi', 'Jake', 'John'. Try looking them up!</p>
    <?php 
        echo '
            <br>
                <form method="post">
                    <p>Search trainers: 
                        <input type="text" name="trainer-query">
                        <input type="submit">
                    </p>
                </form>
        ';  

        // Render table
        $trainerView = new TrainersView();
        if (isset($_POST["trainer-query"])){
            $name = $_POST["trainer-query"];
            $trainerView->trainersTableByName($name);
        }

    ?>
    </div>
</body>
</html>