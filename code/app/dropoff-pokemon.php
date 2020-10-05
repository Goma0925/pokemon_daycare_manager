<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/PokemonView.php';
    ?>
    <?php   
        $commonView = new CommonView();
        // Replace the page title here. It generates title and CSS & JavaScript links.
        $commonView->header("Pokemon Drop Off"); 
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%; margin-right:5%; margin-top: 25px;">
        <h2>Select [Trainer name]'s pokemon to drop off or add a new pokemon</h2><br>
    <?php 
        $pokemonView = new PokemonView();
        if (isset($_GET["trainer"])){
            $trainer_id = $_GET["trainer"];
            $action = "add_service_record.php"; //Send the pokemon selection post data here.
            $show_acive = false;
            $pokemonView->pokemonSelectionTable($trainer_id, $action, $show_acive);
        }else{
            echo "<p>Error: No trainer specified</p>";
        };

    ?>
        <br>
        <p><a style="float: right;" href="./register-pokemon.php" type="button" class="btn btn-secondary">Register new pokemon</a></p>
    </div>
</body>
</html>