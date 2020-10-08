<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/PokemonView.php';
            // include_once 'controllers/SomeContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        $commonView->header("Register Pokemon"); 
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%; margin-right:5%; margin-top: 25px;">
    <?php 
        $pokemonView = new PokemonView();
        $pokemonView->pokemonRegistrationForm(1);

        echo $_POST["example_two"];
    ?>
    </div>
</body>
</html>