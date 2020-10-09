<?php
    include_once 'utils/Query.php';
    class TrainersModel {
        public function getTrainersByName($name){
            $query = new Query();
            //This function returns all the trainer records that contain the name string
            //It ignores the distinction lowercase & uppercase.
            $sql = "SELECT * FROM Trainers WHERE UPPER(trainer_name) LIKE ?";
            $bindArr = ["%".$name."%"];
            $bindTypeStr = "s";
            $query->setAll($sql,$bindTypeStr,$bindArr);
            $res_container = $query->handleQuery(); 
            return $res_container; 
        }

        public function getTrainerByPokemon(int $pokemon_id){
            // Retrieve a owner trainer of a particular pokemon.
            $query = new Query();
            $sql = "SELECT * FROM Trainers 
                    INNER JOIN Pokemon
                    USING (trainer_id)
                    WHERE pokemon_id = ?";
            $bindArr = [$pokemon_id];
            $bindTypeStr = "i";
            $query->setAll($sql,$bindTypeStr,$bindArr);
            $res_container = $query->handleQuery();
            return $res_container; 
        }

        public function emailExists($email){
            $query = new Query();
            $sql = "SELECT trainer_id
                        FROM Trainers
                        WHERE email = ?;";
            //Construct bind parameters
            $bindTypeStr = "s"; 
            $bindArr = Array($email);
            $query->setAll($sql,$bindTypeStr,$bindArr);
            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();

            //Get the number of rows to check if the record with the email exsits.
            $result = $resultContainer->get_mysqli_result();  
            $row_num = $result->num_rows;

            //Return true if the record with the email exsits.
            if ($row_num > 0){
                return true;
            }else{
                return false;
            }
        }

        public function phoneExists($phone_number){
            $query = new Query();
            $sql = "SELECT trainer_id
                        FROM Trainers
                        WHERE phone = ?;";
            //Construct bind parameters
            $bindTypeStr = "s"; 
            $bindArr = Array($phone_number);
            $query->setAll($sql,$bindTypeStr,$bindArr);

            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();

            //Get the number of rows to check if the record with the phone number exsits.
            $result = $resultContainer->get_mysqli_result();  
            $row_num = $result->num_rows;

            //Return true if the record with the phone number exsits.
            if ($row_num > 0){
                return true;
            }else{
                return false;
            }
        }

        public function addUser($name, $phone_number, $email){
            $query = new Query();
            $sql = 'INSERT INTO Trainers (trainer_name, phone, email)
                        VALUE (?, ?, ?);
            ';
            //Construct bind parameters
            $bindTypeStr = "sss"; 
            $bindArr = Array($name, $phone_number, $email);
            $query->setAll($sql,$bindTypeStr,$bindArr);

            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();

            //Return the result container that contains a success flag and mysqli_result.
            return $resultContainer;
        }

        public function getTrainerByAttr(int $trainer_id = null,
                                        string $email = null,
                                        string $phone = null) {
            $query = new Query();
            $base_sql = "SELECT trainer_id, email, phone, trainer_name FROM Trainers"; 
            $query->addToSql($base_sql);

            if (isset($trainer_id)) { // get unique pokemon
                $query->addToSql(" WHERE trainer_id = ?;");
                $query->addBindType("i");
                $query->addBindArrElem($trainer_id);
            } 
            elseif (isset($email)) {
                $query->addToSql("WHERE email LIKE '?';");
                $query->setBindTypeStr("s");
                $bindArr[] = isset($email) ? 
                    $query->addBindArrElem($email) 
                    : 
                    $query->addBindArrElem("%");
            }    
            elseif (isset($phone)) { 
                $query->addToSql("WHERE phone LIKE '?';");
                $query->setBindTypeStr("s");
                $bindArr[] = isset($phone) ? 
                    $query->addBindArrElem($phone) 
                    : 
                    $query->addBindArrElem("%");
            }      
            else {
                throw new Exception("Invalid arguments");
            } 
            $resultContainer  = $query->handleQuery();
            return $resultContainer; 
        }
    }
?>