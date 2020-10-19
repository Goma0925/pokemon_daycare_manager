<?php
    include_once 'utils/Query.php';
    class ServiceRecordsModel { //Make sure to use plural noun for the class name

        public function getServiceRecordColNames() {
            $query = new Query();

            // Call handler
            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'ServiceRecords'";
            $query->addToSql($sql);
            $resultContainer  = $query->handleQuery(); 
            return $resultContainer;
        } 


        public function updateAServiceRecord($start_time, $end_time,$id) {
            $query = new Query();
            $query->addToSql("UPDATE ServiceRecords SET start_time = ?");
            $query->addBindType("s");
            $query->addBindArrElem($start_time);
            if ($end_time != "") {
                $query->addToSql(", end_time = ?");
                $query->addBindType("s");
                $query->addBindArrElem($end_time);
            }
            $query->addToSql(" WHERE service_record_id = ?");
            $query->addBindType("i");
            $query->addBindArrElem($id);

            // $query->printSqlArr();
            // $query->printBindTypeArr();
            // $query->printBindArr();
    
            return $query->handleQuery();
        }

        public function startService(int $trainer_id, int $pokemon_id, string $start_date = null) {
            $query = new Query();
            try {
                if (!isset($trainer_id) || !isset($pokemon_id)) { // we can just infer date otherwise
                    throw new Exception("Error: insufficient arguments provided");  
                } 
                $start_date = !isset($start_date) ? date('Y-m-d H:i:s') : $start_date; 
                $sql = "INSERT INTO ServiceRecords(start_time, pokemon_id, trainer_id) 
                               VALUES (?,?,?);"; 
                $bindArr = [$start_date, $pokemon_id, $trainer_id];
                $bindTypeStr = "sii";
                $query->setAll($sql, $bindTypeStr, $bindArr);
                $resultContainer  = $query->handleQuery(); // returns ResultContainer
                return $resultContainer; // return type ResultContainer
            }
            catch (Exception $e) {
                return;
            }
        }

        public function endService(DateTime $end_date, int $service_record_id) {
            $query = new Query();
            $sql = "UPDATE ActiveServiceRecords 
                        SET end_time = ? WHERE service_record_id = ?;"; 
            $bindArr = [$end_date->format('Y-m-d h:i:s'), $service_record_id];
            $bindTypeStr = "si";
            $query->setAll($sql, $bindTypeStr, $bindArr);
            $resultContainer = $query->handleQuery(); // returns ResultContainer
            return $resultContainer; // return type ResultContainer
        }

        /* This is the parent method of several methods.
        $service_record_id : a unique service record id
        $trainer_id : a unique trainer id
        $pokemon_id : a unique pokemon id
        $date_range[2] : start date of a service record
            [$start_date, $end_date]
            If only one of the two is provided,
            that exact date will be used for an exact
            date search.
            If both provided, services records on the 
            range will be targeted (end dates included).
        $active_degree : degree active of service record
            0 : inactive
            1 : active
            2 : both (inactive and active)
            default : 2
        Always provide $active_degree if you do not want to target active records!
        Additionally, there are methods built on top of this method; these are 
        common operations. For anything more complex, use this method. 
        */
        public function getServiceRecords(int $service_record_id = null,
                                          int $trainer_id = null, 
                                          int $pokemon_id = null, 
                                          $date_range = null,
                                          $active_degree = 1) {

            $query = new Query();
            // Declare query assembling vars
            $s_where_conditions = [];
            
            $query->addToSql("SELECT service_record_id, start_time, end_time, pokemon_id,
            trainer_id FROM ");

            // Setting the table for correct active degree
            if ($active_degree == 0) { // inactive only
                $query->addToSql("InactiveServiceRecords ");
            }
            elseif ($active_degree == 1) { // active only
                $query->addToSql("ActiveServiceRecords ");
            }
            elseif ($active_degree == 2) { // both inactive and active
                $query->addToSql("ServiceRecords ");
                
            }
            else {
                // If time, define custom exception handler (this one is developer spec)
                throw new Exception("Invalid argument for '$active_degree'.");
            }

            if (isset($service_record_id) || isset($pokemon_id) || isset($trainer_id)) {
                $query->addToSql("WHERE ");    
            }
            // Start assembling query
            if (isset($service_record_id)) { // search by service_record_id
                $query->addToSql("service_record_id = ?");
                $query->addBindArrElem($service_record_id);
                $query->addBindType("i");
            }
            else {
                if (isset($pokemon_id)) {
                    $s_where_conditions[] = "pokemon_id = ?";
                    $query->addBindArrElem($pokemon_id);
                    $query->addBindType("i");
                }
                if (isset($trainer_id)) {
                    $s_where_conditions[] = "trainer_id = ?";
                    $query->addBindArrElem($trainer_id);
                    $query->addBindType("i");
                }         

                // Append conditions to base_sql
                $n_conditions = count($s_where_conditions)-1;
                if ($n_conditions >= 0) {
                    for($n = 0; $n < $n_conditions-1; $n++) { // add last condition with &&
                        // $base_sql = $base_sql.$condition." && ";
                        $query->addToSql($s_where_conditions[$n]." && ");
                    }
                    // Add last condition condition to base_sql
                    $query->addToSql($s_where_conditions[$n_conditions]);    
   
                }           
            }
            $query->addToSql(";");    
            $resultContainer = $query->handleQuery(); // returns ResultContainer
            return $resultContainer; // return type ResultContainer
        }

        public function getRatings() {

        }
           
        // COMMON GET BYS (DEFAULTS TO ALL STATUS, change as needed)
        public function getServiceRecordsByPokemonID($pokemon_id, $active_degree = 2) {
            return $this->getServiceRecords(null,null,$pokemon_id,null,$active_degree);
        }

        public function getServiceRecordsByTrainerID($trainer_id, $active_degree = 2) {
            return $this->getServiceRecords(null,$trainer_id,null,null,$active_degree);    
        }

        public function getServiceRecordByID($service_record_id, $active_degree = 2) {
            return $this->getServiceRecords($service_record_id,null,null,null,$active_degree);    
        }

        public function getServiceRecordsStartDate($start_date, $active_degree = 2) {
            return $this->getServiceRecords(null,null,null,$start_date,$active_degree);    
        }

        // GET ALLS
        public function getAllActiveServiceRecords() {
            return $this->getServiceRecords(null,null,null,null,1);
        }

        public function getAllInactiveServiceRecords() {
            return $this->getServiceRecords(null,null,null,null,0);
        }

        public function getAllServiceRecords() {
            return $this->getServiceRecords(null,null,null,null,2);
        }

        public function getElaborateActiveServiceRecords(){
            //Get inactive service records with trainer name and pokemon name
            $query = new Query();
            $sql = "SELECT service_record_id, start_time, end_time, nickname, breedname, trainer_name
                    FROM ActiveServiceRecords
                    INNER JOIN Trainers 
                        USING (trainer_id) 
                    INNER JOIN Pokemon 
                        USING (pokemon_id);";
            $query->addToSql($sql);
            $resultContainer = $query->handleQuery();
            return $resultContainer;
        }

        public function getElaborateActiveServiceRecordById($service_record_id){
            //Get inactive service records with trainer name and pokemon name
            $query = new Query();
            $sql = "SELECT service_record_id, start_time, end_time, nickname, breedname, trainer_name
                    FROM ActiveServiceRecords
                    INNER JOIN Trainers 
                        USING (trainer_id) 
                    INNER JOIN Pokemon 
                        USING (pokemon_id)
                    WHERE service_record_id = ?";
            $query->addToSql($sql);
            $query->addBindArrElem($service_record_id);
            $query->addBindType("i");
            $resultContainer = $query->handleQuery();
            return $resultContainer;
        }
    }
?>