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

        public function buildTableForm($action, $method, $row_headers, $table_data, $input_value) {
            // make sure $input value matches a real column name
            // makesure rowheaders equals number of rows in table 
            // restrict method to post and get
            $field_info = $table_data->get_mysqli_result()->fetch_fields(); 
            // var_dump($field_info)
            echo '
                <form action="'.$action.'" method="'.$method.'">
                    <table class="table">
                        <thead>'; 
                        echo '<tr>';
            foreach ($row_headers as $rhead) {
                echo '  
                                <th scope="col">'.$rhead.'</th>        
                ';
            }
            echo '          </tr>';
            echo "     </thead>";
            echo "     <tbody>";
            while ($row = $table_data->get_mysqli_result()->fetch_assoc()) {
                echo '
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                        type="radio" name="'.strtolower($field_info[0]->table).'" 
                                        value="'.$row[$input_value].'"> 
                                    </div>
                                </td>
                            ';
                foreach ($field_info as $f) {
                    echo '
                                <td>'.$row[$f->name].'</td>
                        ';
                }
                echo '          </tr>';

            }    
            echo '  </tbody>
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
                $this->buildTableForm($action,"get", 
                    ["Edit","RecordID","Start Date", "End Date", "PokemonID", "TrainerID"],
                    $resultContainer,"service_record_id");
            }
            else { // do not render at all (maybe render some error, just depends)
                var_dump($resultContainer->getErrorMessages());
            } 
        }

        public function checkInConfirmationBox(int $trainer_id, int $pokemon_id, string $action, string $method, Array $form_params){
            //Renders the confirmation box to confirm the check in info and submit the request.
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
                $pokemon_breed = $pokemon_record? $pokemon_record["breedname"]: "Pokemon name not found";

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
        }
 
        public function checkInCompletionBox(int $trainer_id, string $trainer_name, $pokemon_nickname){
            echo '
                <div class="jumbotron">
                    <h1 class="display-4">Check-In Complete!</h1>
                    <p class="lead">The check-in has been recorded. Go to Check-In/Out tab to check out the customer.</p>
                    <hr class="my-4">
                    <p class="lead">
                        <a class="btn btn-info" href="select-pokemon.php?redirect-to=check-in-confirmation&active=false&trainer='.$trainer_id.'" role="button">Check-in other pokemon</a>
                    </p>
                </div>';
        }

    }
?>