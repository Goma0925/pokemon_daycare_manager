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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js">     
    </script>

    <script> 
        $(document).ready(function() {
            var date_input_val = $("#date_changed").val();
            console.log(date_input_val);
            $("#td_date_changed").hide();

            $("#insert_new").click(function(){
                $("input#date_changed").attr("type","date");
                $("input#date_changed").attr("min",date_input_val);
                $("#td_date_changed").show();
                console.log("clicked");
                $("#insert_new").hide();
                $("#td_date_changed").after(
                    ' <td class="new" id="price_per_day"> <input id="price_per_day_float" name="states[price_per_day]" placeholder="20.25"> </td> ' +
                    ' <td class="new" id="max_pokemon_per_trainer"> <input id="price_per_egg_float" type="number" name="states[max_pokemon_per_trainer]" placeholder="4"> </td> ' +
                    ' <td class="new" id="flat_egg_price"> <input id="egg_price_float" name="states[flat_egg_price]" placeholder="15.25"> </td> ' +
                    ' <td class="new" > <button id="cancel" class="btn btn-secondary" >Cancel</td>' +
                    ' <td class="new" > <input class="btn btn-secondary" type="submit"> </td>'
                    );  
            }); 

            $(document).on("click","#cancel", function() {
                console.log("doing");
                $("#td_date_changed").hide();
                $(".new").remove();
                $("#state_id").after(
                    '<td class="new" id="td_date_changed" > <input id="date_changed" type="hidden" value="'+date_input_val+'" name="states[date_changed]"></td>'
                );
                $("#insert_new").show();
            });
            $(document).on("change",'input#egg_price_float',checkDecimals);
            $(document).on("change",'input#price_per_day_float',checkDecimals);

            function checkDecimals() {
                var num = parseFloat($(this).val());
                console.log(num)
                var cleanNum = num.toFixed(2);
                $(this).val(cleanNum);
                if(num/cleanNum < 1){
                    $('#error').text('Please enter only 2 decimal places, we have truncated extra points');
                }                
            }
        });

    </script>

    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/BusinessStatesView.php';
            include_once 'controllers/BusinessStatesContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        // Replace the page title here. It generates title and CSS & JavaScript links.
        $commonView->header("Settings"); 
    ?>
</head>
<body>
    <?php 
            $commonView = new CommonView();
            $commonView->navbar();
        ?>

    <div style="margin-left:5%; margin-right:5%; margin-top: 25px;">

    <?php 
        $businessStatesView = new BusinessStatesView();
        $businessStatesContr = new BusinessStatesContr();


        if (isset($_POST["states"])) {
            $states = $_POST["states"];
            $date_changed = $states["date_changed"];
            $price_per_day = $states["price_per_day"];
            $pokemon_per_trainer = $states["max_pokemon_per_trainer"];
            $egg_price = $states["flat_egg_price"];
            
            $result = $businessStatesContr->addNewBusinessState(
                $date_changed,
                !empty($price_per_day) ? $price_per_day : null,
                !empty($pokemon_per_trainer) ? $pokemon_per_trainer : null,
                !empty($egg_price) ? $egg_price : null);

            if ($result->isSuccess()) {
                $success = $result->getSuccessValues()["bstate"];
                echo $success;
                $businessStatesView->renderBusinessStatesTable();
            }
            else {
                $failure = $result->getErrorMessages();
                foreach ($failure as $val) {
                    echo $val;
                }
                $businessStatesView->renderBusinessStatesTable();
            }
        }
        else {
            $businessStatesView->renderBusinessStatesTable();
        }


    ?>
    </div>
</body>
</html>
