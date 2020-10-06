<?php
    // include_once 'models/Database.php'; // we already extend database, we also never use its methods here
    include_once 'utils/Query.php';
    class ServiceRecordsModel extends Database { //Make sure to use plural noun for the class name

        public function getServiceRecordColNames() {
            $query = new Query();

            // Call handler
            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'ServiceRecords'";
            $query->addToSql($sql);
            $resultContainer  = $query->handleQuery(); 
            return $resultContainer;
        } 

        public function startService(int $trainer_id, int $pokemon_id, string $start_date = null) {
            // Declare vars
            $query = new Query();
            try {
                if (!isset($trainer_id) || !isset($pokemon_id)) { // we can just infer date otherwise
                    throw new Exception("Error: insufficient arguments provided");  
                } 
                !isset($start_date) ? (new DateTime())->format('Y-m-d H:i:s') : $start_date; 
                $sql = "INSERT INTO ServiceRecords(start_time, pokemon_id, trainer_id) 
                               VALUES (?,?,?);"; 
                $bindArr = [$start_date, $pokemon_id, $trainer_id];
                $bindTypeStr = "sii";
                // $query->setBindArr([$start_date, $pokemon_id, $trainer_id]);
                // $query->addToSql($sql);
                // $query->setBindStr("sii");
                $query->setAll($sql, $bindTypeStr, $bindArr);
                $resultContainer  = $query->handleQuery(); // returns ResultContainer
                return $resultContainer; // return type ResultContainer
            }
            catch (Exception $e) {
                return;
            }
        }

        public function endService(string $end_date, int $service_record_id) {
            try {
                // Declare vars
                $query = new Query();
                if (!isset($end_date) || !isset($service_record_id)) {
                    throw new Exception("Error: insufficient arguments provided");  
                } 
                $sql = "UPDATE ActiveServiceRecords 
                            SET end_time = ? WHERE service_record_id = ?;"; 
                $bindArr = [$end_date, $service_record_id];
                $bindTypeStr = "si";
                // $query->addToSql("UPDATE ActiveServiceRecords 
                //                  SET end_time = ? WHERE service_record_id = ?;");
                // $query->setBindArr([$end_date, $service_record_id]);
                // $query->setBindTypeStr("si");
                $query->setAll($sql, $bindTypeStr, $bindArr);

                // $resultContainer = $this->handleQuery($sql,$bindTypeStr,$bindArr); // returns ResultContainer
                $resultContainer = $query>handleQuery(); // returns ResultContainer
                return $resultContainer; // return type ResultContainer
            }
            catch (Exception $e) { // we will not return if this function is not called correctly
                // this should be logged because of the global logging settings set (thanks amon)
                return; // halt function 
            }
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
                                          $active_degree = 1
                                          ) {

            $query = new Query();
            // Declare query assembling vars

            // $resultContainer = null;
            // $bindArr = Array();
            // $bindTypeStr = "";
            // $s_where_conditions = [];
            
            // $base_sql = "SELECT service_record_id, start_time, end_time, pokemon_id,
            // trainer_id FROM ";

            $query->addToSql("SELECT service_record_id, start_time, end_time, pokemon_id,
            trainer_id FROM ");

            // Setting the table for correct active degree
            if ($active_degree == 0) { // inactive only
                // $base_sql = $base_sql."InactiveServiceRecords";
                $query->addToSql("InactiveServiceRecords");
            }
            elseif ($active_degree == 1) { // active only
                // $base_sql = $base_sql."ActiveServiceRecords";
                $query->addToSql("ActiveServiceRecords");
            }
            elseif ($active_degree == 2) { // both inactive and active
                // $base_sql = $base_sql."ServiceRecords";
                $query->addToSql("ServiceRecords");
                
            }
            else {
                // If time, define custom exception handler (this one is developer spec)
                throw new Exception("Invalid argument for '$active_degree'.");
            }

            // Start assembling query
            if (isset($service_record_id)) { // search by service_record_id
                $s_where_conditions[] = "service_record_id = ?";
                // $bindArr[] = $service_record_id;
                // $bindStr = $bindStr."i";
                $query->addBindArrElem($service_record_id);
                $query->addBindType("i");
            }
            else {
                if (isset($pokemon_id)) {
                    $s_where_conditions[] = "pokemon_record_id = ?";
                    // $bindArr[] = $pokemon_id;
                    // $bindStr = $bindStr."i";
                    $query->addBindArrElem($pokemon_id);
                    $query->addBindType("i");
                }

                if (isset($trainer_id)) {
                    $s_where_conditions[] = "trainer_id = ?";
                    // $bindArr[] = $trainer_id;
                    // $bindStr = $bindStr."i";
                    $query->addBindArrElem($trainer_id);
                    $query->addBindType("i");
                }         

                // if (isset($start_date)) { // leave out for now
                //     // $s_where_conditions[] = "start_time = ?"; // what type?
                //     // $bindArr[] = $start_date;
                //     // $bindStr."s";
                //     echo "Do stuff";
                // } 

                // Append conditions to base_sql
                $n_conditions = count($s_where_conditions)-1;
                if ($n_conditions >= 0) {
                    for($n = 0; $n < $n_conditions-1; $n++) { // add last condition with &&
                        // $base_sql = $base_sql.$condition." && ";
                        $query->addToSql($s_where_conditions[$n]." && ");
                    }
                    // Add last condition condition to base_sql
                    // $base_sql = $base_sql.$s_where_conditions[$n_conditions]; 
                    $query->addToSql($base_sql.$s_where_conditions[$n_conditions]);    
   
                }           
            }
            // $base_sql = $base_sql.";"; 
            $query->addToSql(";");    
            $resultContainer = $query->handleQuery(); // returns ResultContainer
            return $resultContainer; // return type ResultContainer
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
            return $this->getServiceRecords();
        }

        public function getAllInactiveServiceRecords() {
            return $this->getServiceRecords(null,null,null,null,0);
        }

        public function getAllServiceRecords() {
            return $this->getServiceRecords(null,null,null,null,2);
        }
    }
?>