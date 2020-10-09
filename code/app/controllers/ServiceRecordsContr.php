<?php 
    include_once 'models/ServiceRecordsModel.php';
    include_once 'models/TrainersModel.php';
    include_once 'models/PokemonModel.php';
    include_once 'utils/ResultContainer.php';
    class ServiceRecordsContr {//Make sure to use plural noun for the class nam
        private $serviceRecordsModel;
        private $trainersModel;
        private $pokemonModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            //eg:  $this->trainersModel = new TrainersModel();
            $this->serviceRecordsModel = new ServiceRecordsModel();
            $this->trainersModel = new TrainersModel();
            $this->pokemonModel = new PokemonModel();
        }

        
        // start_time DATETIME NOT NULL,
        // end_time DATETIME, 
        // pokemon_id INT,
        // trainer_id INT, 
        // NOTE: end time will be set later

        public function addServiceRecord($start_time = null, 
                                        int $pokemon_id,
                                        int $trainer_id) {
            // Add service record to database

            //Set current date time if not set. 
            $dt = !isset($start_time) ? date('Y-m-d H:i:s') : $start_date; 

            $resultContainer = new ResultContainer(); //ResultContainer for all validation errors.

            //Check if the trainer exists
            if (true){ //Always execute this.
                $trainerByIdResult = $this->trainersModel->getTrainerByAttr(
                                                        $trainer_id, //int $trainer_id
                                                        null,        //string $email
                                                        null);       //string $phone
                if ($trainerByIdResult->isSuccess()){
                    if ($trainerByIdResult->get_mysqli_result()->num_rows < 1){
                        $resultContainer->setFailure();
                        $resultContainer->addErrorMessage("Trainer does not exist.");
                    }
                }
                else{
                    // Database error
                    $resultContainer->setFailure();
                    $resultContainer->mergeErrorMessages($trainerByIdResult);
                }
            }
            //Check if the incoming data of the pokemon's owner is the incoming trainer & 
            //if the pokemon exists.
            if ($resultContainer->isSuccess()){
                $pokemonByTrainerResult = $this->trainersModel->getTrainerByPokemon($pokemon_id);
                if ($pokemonByTrainerResult->isSuccess()){
                    $owner_record = $pokemonByTrainerResult->get_mysqli_result()->fetch_assoc();
                    if ($owner_record==null){
                        $resultContainer->setFailure();
                        
                        $resultContainer->addErrorMessage("Pokémon does not exist.");
                    }
                    $owner_id = $owner_record? $owner_record["trainer_id"]:"-1";
                    if ($owner_id != $trainer_id){
                        $resultContainer->setFailure();
                        
                        $resultContainer->addErrorMessage("Only the owner of the Pokémon can check-in his/her Pokémon.");
                    }
                }else{
                    // Database error
                    $resultContainer->setFailure();
                    $resultContainer->mergeErrorMessages($pokemonByTrainerResult);
                    
                }
            }
            //Check the pokemon is not active already.
            if ($resultContainer->isSuccess()){
                $activePokemonResult = $this->pokemonModel->getAllActivePokemon();
                if ($activePokemonResult->isSuccess()){
                    $done = false;
                    while ($row = $activePokemonResult->get_mysqli_result()->fetch_assoc()){
                        if ($row["pokemon_id"] == $pokemon_id){
                            $resultContainer->setFailure();
                        }
                    }
                    if (!$resultContainer->isSuccess()){
                        $resultContainer->addErrorMessage("Pokemon is already in daycare.");
                    }
                }else{
                    // Database error
                    $resultContainer->setFailure();
                    $resultContainer->mergeErrorMessages($activePokemonResult);
                }
            }
            //Check if the trainer has less than 2 active services.
            if ($resultContainer->isSuccess()){
                $resultRecordNumFetch = $this->serviceRecordsModel->getServiceRecords(
                                                            null,//service_record_id
                                                            $trainer_id, //trainer_id 
                                                            null, //pokemon_id 
                                                            null,//date_range 
                                                            1);//active_degree

                if ($resultRecordNumFetch->isSuccess()){
                    $num_records = $resultRecordNumFetch->get_mysqli_result()->num_rows;
                    if ($num_records >= 2){
                        $resultContainer->setFailure();
                        $resultContainer->addErrorMessage("A trainer cannot have more than two Pokémon in daycare at the same time.");
                        
                    }
                }else{
                    // Database error
                    $resultContainer->setFailure();
                    $resultContainer->mergeErrorMessages($resultRecordNumFetch);
                    
                }
            }
            //Once all the validation is done, insert a new service record.
            if ($resultContainer->isSuccess()){
                $insertionResult = $this->serviceRecordsModel->startService($trainer_id,$pokemon_id,$start_time);
                if (!$insertionResult->isSuccess()){
                    $resultContainer->mergeErrorMessages($insertionResult);
                }
            }
            return $resultContainer;
        }


        // Update service record in database 
        public function updateServiceRecord($start,$end,$service_ids) {
            $length = sizeof($service_ids);
            // var_dump($res);
            for ($i = 0; $i < $length ; $i++) {
                $start_time = str_replace("T"," ",$start[$i]);
                $end_time = "";
                if ($end[$i] != "") { 
                    $end_time = str_replace("T"," ",$end[$i]);
                }
                $service_record_id = $service_ids[$i];
                // var_dump($start_time);
                // var_dump($end_time);
                $result = $this->serviceRecordsModel->updateAServiceRecord($start_time, $end_time, $service_record_id);
            }
        }

        public function endServiceRecord($start_time = null,  int $pokemon_id, int $trainer_id){
            //Put an end date to the target service record.
        }
    }

?>