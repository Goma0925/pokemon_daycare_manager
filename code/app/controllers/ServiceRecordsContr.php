<?php 
    include_once 'models/ServiceRecordsModel.php';
    include_once 'utils/ResultContainer.php';
    class ServiceRecordsContr {//Make sure to use plural noun for the class nam
        private $serviceRecordsModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            //eg:  $this->trainersModel = new TrainersModel();
            $this->serviceRecordsModel = new ServiceRecordsModel();
        }

        
        // start_time DATETIME NOT NULL,
        // end_time DATETIME, 
        // pokemon_id INT,
        // trainer_id INT, 
        // NOTE: end time will be set later

        // Add service record to database
        // Do we just return true or false, or return result object?
        // Where should we enforce that the date fields of the service
        // record form are required, or checkbox is selected so use current
        // datetime stamp. 
        public function addServiceRecord($start_time = null, 
                                        $pokemon_id,
                                        $trainer_id) {
            $resultContainer = new ResultContainer();  
            try { 
                $dt = isset($start_time) ? 
                    DateTime::createFromFormat("Y-m-d H:i:s'", $start_time) 
                    :
                    true; // we will handle the datetime entry
                if ($dt === false) {
                    throw new Exception("Bad date format!"); 
                }
                else {
                    $resultContainer->mergeErrorMessages($this->serviceRecordsModel->startService($trainer_id,$pokemon_id,$start_time));
                    return $resultContainer;
                }
            }
            catch (Exception $e) { // We control datetime format with form, so this should
                                   // not happen. 
                $res->isFailure();
                return $resultContainer; // gracefully return (do not want to harm children calls)
            }
        }


        // Update service record in database 
        public function updateServiceRecord($res) {

            // do some validation and error checking !!
            // check if actual date

            // var_dump($res);
            foreach($res as $val) {
                if ($val != "default") {
                    // var_dump($val);
                    $data_id_col = explode("|", $val);
                    $data = $data_id_col[0];
                    $id = $data_id_col[1];
                    $col= $data_id_col[2];
                    $result = $this->serviceRecordsModel->updateAServiceRecord($data,$id,$col);
                }
            }
            return true; 
        }

    }

?>