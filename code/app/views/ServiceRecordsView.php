
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
    // $(document).ready(function () {
    //     $('.updaters').hide(); //hide the submit button by default
    // });

    // function updateValue(element,col,id) {
    //     console.log("called");
    //     var selector = ("#").concat(col.concat(id));
    //     var new_val = element.innerText.concat("|",id).concat("|",col);
    //     $(selector).attr("value",new_val);
    //     if (new_val) {
    //         $('input#'.concat(id)).show();
    //     }
       
    // }
</script>


<?php
    include_once 'models/ServiceRecordsModel.php';
    include_once 'models/TrainersModel.php';
    include_once 'models/PokemonModel.php';

    class ServiceRecordsView { //Make sure to use plural noun for the class name
        private $serviceRecordsModel;
        private $trainersModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            // e.g:   $this->trainersModel = new TrainersModel();
            $this->serviceRecordsModel = new ServiceRecordsModel();
            $this->trainersModel = new TrainersModel();
        }


        // CODE LOOKS UGLY, BUT IT BUILDS THE SERVICE RECORDS TABLE WITH EDITABLE DATE FIELDS 
        public function buildTableForm($action, $method, $row_headers, $table_data, $input_value) {
            $field_info = $table_data->get_mysqli_result()->fetch_fields(); 
            echo '
                <form id="services" action="'.$action.'" method="'.$method.'">
                    <table class="table">
                        <thead>
                            <th scope="col">RecordID</th>      
                            <th scope="col">Start Date</th>     
                            <th scope="col">End Date</th>  
                            <th scope="col">PokemonID</th>  
                            <th scope="col">TrainerID</th> 
                            <th scope="col">Save/Update</th> 
                            </tr>
                        </thead>
                        <tbody>';
                    //     <div contenteditable="true" onBlur=updateValue(this,"end_time","'.$id.'")> 
                    //     '.$row["end_time"].'
                    // </div>
                //     <input type="hidden" 
                //     id="end_time'.$id.'" 
                //     name="res[]" value="default"
                //     <input type="hidden" 
                //     id="start_time'.$id.'" 
                //     name="res[]" value="default"
                // >
                // <div contenteditable="true" onBlur=updateValue(this,"start_time"","'.$id.'")>
                //     '.$row["start_time"].'
                // </div>
                // >
            while ($row = $table_data->get_mysqli_result()->fetch_assoc()) { // for each row in table
                $id = $row["service_record_id"]; // unique service id

                $date_end = $row["end_time"];
                $date_strt = $row["start_time"];

                $temp_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $date_strt);
                $temp_start_date_out = $temp_start_date->format('Y-m-d H:i:s');
                $start = str_replace(" ","T",$temp_start_date_out);

                $end;
                if ($date_end != NULL) {
                    $temp_end_date = DateTime::createFromFormat('Y-m-d H:i:s', $date_end);
                    $temp_end_date_out = $temp_end_date->format('Y-m-d H:i:s');
                    $end = str_replace(" ","T",$temp_end_date_out);
                }
                else {
                    $end = null;
                }
    
                echo '<tr> 
                        <td>'.$row["service_record_id"].'</td>
                        <input type="hidden" name="service_id[]" value="'.$id.'">
                        <td>
                            <input type="datetime-local"  name="start[]" value="'.$start.'" >
                        </td>
                        <td>
                            <input type="datetime-local"  name="end[]" value="'.$end.'" >
                        </td>
                       
                        </td>
                        <td>'.$row["pokemon_id"].'</td>
                        <td>'.$row["trainer_id"].'</td>
                        <td>
                            <div>
                                <input 
                                    class = "updaters"
                                    id="'.$id.'"
                                    type="submit"  
                                    visibility="hidden"
                                    name="'.$id.'"
                                > 
                            </div>
                        </td>
                    </tr>';
            }    
            echo 
            '       </tbody>
                </table>
            </form>'; 
        }

        public function buildServiceRecordsTable($action, string $by = null, $value = null, $status = null){
            //1. Get data from model
            $resultContainer; 
            $status = intval($status);
            if (!empty($value)) {
                $criteria = $by; 
                if (strpos($criteria, 'id') !== false) { $value = intval($value); }
                switch ($criteria) {
                    case "trainer_id":
                        $resultContainer = $this->serviceRecordsModel->getServiceRecordsByTrainerID($value, $status);
                        break;
                    case "service_record_id":
                        $resultContainer = $this->serviceRecordsModel->getServiceRecordByID($value, $status);
                        break;
                    case "pokemon_id":
                        $resultContainer = $this->serviceRecordsModel->getServiceRecordsByPokemonID($value, $status);
                        break;
                  }
            }
            else {
                switch ($status) {
                    case 0:
                        $resultContainer = $this->serviceRecordsModel->getAllInactiveServiceRecords();
                    break;
                    case 1:
                        $resultContainer = $this->serviceRecordsModel->getAllActiveServiceRecords();

                    break;
                    case 2:
                        $resultContainer = $this->serviceRecordsModel->getAllServiceRecords();
                    break; 
                }
            }

            if ($resultContainer->isSuccess()) { // will render based on what was set above
                // var_dump($resultContainer->get_mysqli_result());
                $this->buildTableForm("service-search.php","post", 
                    ["RecordID","Start Date", "End Date", "PokemonID", "TrainerID","Save/Update"],
                    $resultContainer,"service_record_id");
            }
            else { // do not render at all (maybe render some error, just depends)
                var_dump($resultContainer->getErrorMessages());
            } 
        }

        public function confirmationBox(boolean $is_checkin, int $trainer_id, int $pokemon_id, string $action, string $method, Array $form_params){
            //Renders the confirmation box to confirm the check in/out info and submit the request.
                 //      $action: The "action" value in HTML form. Determines where to send the form request.
                 //      $method: HTTP request to send this trainer_id and pokemon_id in the form.
                // $form_params: An array of (name, value) pairs of form parameters to send with the HTML form.
            $pokemonModel = new PokemonModel();
            $trainerModel = new TrainersModel();
            $pokemonReContainer = $pokemonModel->getPokemonByAttr($pokemon_id = $pokemon_id, $current_level = null, $upper_current_level = null, $nickname = null, $breedname = null, $active = false);
            $trainerReContainer = $trainerModel->getTrainerByAttr($trainer_id = $trainer_id, $email = null, $phone = null) ;
            if ($pokemonReContainer->isSuccess() && $trainerReContainer->isSuccess()){
                $pokemon_record = $pokemonReContainer->get_mysqli_result()->fetch_assoc();
                $pokemon_nickname = $pokemon_record? $pokemon_record["nickname"]: "Pokemon name not found";
                $pokemon_breed = $pokemon_record? $pokemon_record["breedname"]: "Unknown breed";

                $trainer_record = $trainerReContainer->get_mysqli_result()->fetch_assoc();
                $trainer_name = $trainer_record? $trainer_record["trainer_name"]: "No trainer name found";
                echo '
                <div class="jumbotron">
                    <form action="'.$action.'" method="'.$method.'">
                        <input type="hidden" name="trainer" value="'.$trainer_id.'">
                        <input type="hidden" name="trainer_name" value="'.$trainer_name.'">
                        <input type="hidden" name="pokemon" value="'.$pokemon_id.'">
                        <input type="hidden" name="pokemon_nickname" value="'.$pokemon_nickname.'">
                        ';
                        //Render hidden input based on $form_params
                        foreach ($form_params as $name=>$value){
                            echo '
                            <input type="hidden" name="'.$name.'" value="'.$value.'">
                            ';
                        };
                
                echo   '<h2 class="display-4">Check-in confirmation</h2>
                        <p class="lead">Please confirm the information below is correct.</p>
                        <hr class="my-4">
                        <p><b>Trainer</b>&nbsp;&nbsp;&nbsp;&nbsp;: '.$trainer_name.'</p>
                        <p><b>Pokemon</b>: '.$pokemon_nickname.' ('.$pokemon_breed.')</p>
                        <p class="lead" style="float:right;">
                            <a class="btn btn-info" href="select-pokemon.php?redirect-to=check-in-confirmation&active=false&trainer='.$trainer_id.'" role="button">Select other pokemon</a>
                            <button class="btn btn-info" type="submit">Check-In</button>
                        </p>
                    </form>
                </div>
                ';
            }
            $pokemonReContainer->mergeErrorMessages($trainerReContainer);
            return $pokemonReContainer;
        }
 
        public function checkInCompletionBox(int $trainer_id, string $trainer_name, $pokemon_nickname){
            echo '
                <div class="jumbotron">
                    <h1 class="display-4">Check-In Complete!</h1>
                    <p class="lead">The check-in has been recorded. Go to Check-In/Out tab to check out the customer.</p>
                    <hr class="my-4">
                    <p class="lead" style="float:right;">
                        <a class="btn btn-info" href="select-pokemon.php?redirect-to=check-in-confirmation&active=false&trainer='.$trainer_id.'" role="button">Check-in '.$trainer_name.''."'".'s other Pokémon</a>
                    </p>
                </div>';
        }
    }
?>