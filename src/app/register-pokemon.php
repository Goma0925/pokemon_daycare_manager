<?php 
    session_start();
    //Ridirect to login warming to prompt login if not logged in.
    if ($_SESSION["authenticated"]==false){
        header("Location: login-warming.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/PokemonView.php';
            include_once 'views/InputErrorView.php';
            include_once 'controllers/PokemonContr.php';
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
        $pokemonContr = new PokemonContr();
        $inputErrorView = new InputErrorView();
        $action = "register-pokemon.php";
        $method = "POST";
        $form_params = Array();

        if (isset($_GET["trainer"])){
            //Handle the first GET to render pokemon form.
            $trainer_id = $_GET["trainer"];
            $pokemonView->pokemonRegistrationForm($trainer_id, $action, $method, $form_params);
        }else if (isset($_POST["trainer"])){
            //Handle POST request of new pokemon
            $trainer_id = $_POST["trainer"];

            $form_params = Array();
            if (isset($_POST["redirect_to"])){
                $form_params["redirect-to"] = $_POST["redirect-to"];
            }

            //GET move names from POST
            $move_names = Array();
            if ($_POST["move-1"]!=""){
                $move_names[] = $_POST["move-1"];
            };
            if ($_POST["move-2"]!=""){
                $move_names[] = $_POST["move-2"];
            }
            if ($_POST["move-3"]!=""){
                $move_names[] = $_POST["move-3"];
            }
            if ($_POST["move-4"]!=""){
                $move_names[] = $_POST["move-4"];
            };

            $resultAddPokemon = $pokemonContr->addPokemon($_POST["trainer"], $_POST["level"], $_POST["nickname"], $_POST["breedname"],
                                                            $move_names);
            if (!$resultAddPokemon->isSuccess()){
                $errorMessages = $resultAddPokemon->getErrorMessages();
                $inputErrorView->errorBox($errorMessages);
                $pokemonView->pokemonRegistrationForm($trainer_id, $action, $method, $form_params);
            }else{
                //On success, display success message
                $check_in_link = "add-service-record.php";
                //Get the pokemon id of pokemon that has just been created.
                $pokemon_id = $resultAddPokemon->getSuccessValues()["pokemon_id"];
                $pokemonView->registrationSuccessBox($_POST["trainer"], $pokemon_id, $_POST["level"], $_POST["nickname"], $_POST["breedname"],
                $move_names, $check_in_link);
            }
        }else{
            //Show error for requests that are neither GET nor POST.
            $inputErrorView->errorBox(Array("Error: Invalid request. Please start from the beginning."));
        }

    ?>
    </div>
</body>
</html>