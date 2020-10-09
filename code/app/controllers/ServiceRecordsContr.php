<?php 
    include_once 'models/ServiceRecordsModel.php';
    include_once 'models/TrainersModel.php';
    include_once 'utils/ResultContainer.php';
    class ServiceRecordsContr {//Make sure to use plural noun for the class nam
        private $serviceRecordsModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            //eg:  $this->trainersModel = new TrainersModel();
            $this->serviceRecordsModel = new ServiceRecordsModel();
            $this->trainersModel = new TrainersModel();
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

            $resultContainer = $this->trainersModel->getTrainerByPokemon($pokemon_id);
            if ($resultContainer->isSuccess()){
                //Check if the incoming data of the pokemon's owner is the incoming trainer.
                $owner_record = $resultContainer->get_mysqli_result()->fetch_assoc();
                if ($owner_record==null){
                    $resultContainer->setFailure();
                    $resultContainer->addErrorMessage("Pokémon does not exist.");
                }
                $owner_id = $owner_record? $owner_record["trainer_id"]:"No trainer found";
                if ($owner_id != $trainer_id){
                    $resultContainer->setFailure();
                    $resultContainer->addErrorMessage("Only the owner of the Pokémon can check-in his/her Pokémon.");
                }

                //Check if the trainer has less than 2 active services.
                $resultRecordNumFetch = $this->serviceRecordsModel->getServiceRecords(
                                                            $service_record_id = null,
                                                            $trainer_id = $trainer_id, 
                                                            $pokemon_id = null, 
                                                            $date_range = null,
                                                            $active_degree = 1);
                if (!$resultRecordNumFetch->isSuccess()){
                    $num_records = $resultContainer->get_mysqli_result()->num_rows;
                    if ($num_records >= 2){
                        $resultContainer->setFailure();
                        $resultContainer->addErrorMessage("A trainer cannot have more than two Pokémon in daycare at the same time.");
                    }
                }

                //Once all the validation is done, insert a new service record.
                if ($resultContainer->isSuccess()){
                    $resultContainer->mergeErrorMessages($this->serviceRecordsModel->startService($trainer_id,$pokemon_id,$start_time));
                }
            }else{
                $resultContainer->addErrorMessages("Trainer does not exist.");
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
    }

?>