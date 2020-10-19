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
            include_once 'views/ServiceRecordsView.php';
            include_once 'views/PokemonView.php';
            include_once 'controllers/ServiceRecordsContr.php';
            include_once 'views/AuthView.php';
            include_once 'controllers/AuthContr.php';
            // include_once 'controllers/SomeContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        // Replace the page title here. It generates title and CSS & JavaScript links.
        $commonView->header("Page title not set"); 
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
        $pokemonView->activePokemonDropdown("s", true);
        if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"]==false){
            if ($_SESSION["authenticated"]==false){
                echo "au";
            }else{
                echo "ut";
            }
            if(!isset($_SESSION["authenticated"]) ){
                echo "aksdkf";
            }else{
                echo "jkladf";
            }
            // header("Location: login-warming.php");
        }
        $authView = new AuthView();
        if (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"]==true){
            


        }else{
            $authView->loginWarning();
        }
        // $serviceRecordsView = new ServiceRecordsView();
        // $action = "manual-view-test.php";
        // $method = "GET";
        // $form_params = Array();
        // $serviceRecordsView->serviceSelectionTableAll($action, $method, $form_params);

        // $serviceRecordsView->checkOutConfirmationBox(1, $action, $method, $form_params);

        // $serviceRecordsView->checkOutCompletionBox();

        // $serviceRecordsContr = new ServiceRecordsContr();
        // $serviceRecordsContr->endServiceRecord(3);
    ?>
    </div>
</body>
</html>