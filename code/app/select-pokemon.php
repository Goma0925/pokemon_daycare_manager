<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/InputErrorView.php';
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
        <h2>Please select a pokemon:
            <?php 
                if ($_GET["redirect-to"] == "check-in-pokemon"){
                    //Render a button to register a new pokemon for check-in flow.
                    echo '<a style="float: right; margin-bottom:10px;" href="./register-pokemon.php?trainer='.$_GET["trainer"].'" type="button" class="btn btn-secondary">Register new pokemon</a>';
                }
            ?>
        </h2><br>
    <?php
        //A. Construct header


        $pokemonView = new PokemonView();
        $inputErrorView = new InputErrorView();
        //B. Show pokemon using pokemon selection table.
        if (isset($_GET)){
            //1. Determine if it shows active or inactive pokemon
            $show_active_pokemon;
            if ($_GET["active"] == "true"){
                $show_active_pokemon = true;
            }else if($_GET["active"] == "false"){
                $show_active_pokemon = false;
            }else{
                $inputErrorView->errorBox(Array("Invalid Request"));
            };

            //2. Define the method of pokemon selection table's form on submission.
            $method = "GET";

            //3. Construct pokemon selection table according to the redirect-to destination parameter.
            switch ($_GET["redirect-to"]) {
                //Render pokemon selection pokemon for check-in if $_GET["redirect-to"]="check-in-pokemon"
                case "check-in-pokemon": 
                    //if the trainer param specifies a particular trainer, render pokemon of the trainer
                    $action = "add-service-record.php"; //Send the pokemon selection post data here.
                    $trainer_id = $_GET["trainer"];
                    $form_params = Array(
                        "trainer"=>$trainer_id,
                    );//This can be modified if you want to add HTML form values in PokemonSelectionTable
                    $pokemonView->pokemonSelectionTableByTrainer($trainer_id, $show_active_pokemon, $action, $method, $form_params);

                break;

                case "check-out-pokemon": 
                    //if the trainer param specifies a particular trainer, render pokemon of the trainer
                    $action = "end-service-record.php"; //Send the pokemon selection post data here.
                    $trainer_id = $_GET["trainer"];
                    $form_params = Array(
                        "trainer"=>$trainer_id,
                    );//This can be modified if you want to add HTML form values in PokemonSelectionTable
                    $pokemonView->pokemonSelectionTableByTrainer($trainer_id, $show_active_pokemon, $action, $method, $form_params);

                break;
            }

        }else{
            //If this shows on UI, redirect-to parameter is not provided in URL.
            $inputErrorView->errorBox(Array("Invalid Request"));
        };

    ?>
    </div>
</body>
</html>