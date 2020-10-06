<?php
    include_once 'models/Database.php';
    include_once 'utils/Query.php';

    class TrainersModel extends Database {
        public function getTrainersByName($name){
            $query = new Query();
            //This function returns all the trainer records that contain the name string
            //It ignores the distinction lowercase & uppercase.
            $sql = "SELECT * FROM Trainers WHERE UPPER(trainer_name) LIKE ?";
            $bindArr = [$name];
            $bindTypeStr = "s";
            $query->setAll($sql,$bindTypeStr,$bindArr);
            $res_container = $query->handleQuery(); // positional or explicitly state
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
    }
?>