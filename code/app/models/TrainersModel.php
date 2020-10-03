<?php
    include 'models/Database.php';
    class TrainersModel extends Database {
        public function getTrainersByName($name){
            //This function returns all the trainer records that contain the name string
            //It ignores the distinction lowercase & uppercase.
            $name = "%".$name."%";
            $sql = 'SELECT * FROM Trainers WHERE UPPER(trainer_name) LIKE ?';
            $stmt;
            if (!$stmt = $this->connect()->prepare($sql)){
                echo "Prepare statement failed<br>";
            }
            if (!$stmt->bind_param("s", $name)){
                echo "Parameter binding failed<br>";
            }
            if (!$stmt->execute()){
                echo "Query execution failed<br>";
            }
            $result = $stmt->get_result();
            $this->close();
            return $result;
        }

        public function emailExists($email){
            $qryResultContainer = new ResultContainer();
            $sql = 'SELECT trainer_id
                        FROM Trainers
                        WHERE email = ?;
            ';
            $stmt;
            if (!$stmt = $this->connect()->prepare($sql)){
                $qryResultContainer->addErrorMessage("Prepare statement failed.");
                $qryResultContainer->setFailure();
            }
            if (!$stmt->bind_param("s", $email)){
                $qryResultContainer->addErrorMessage("Parameter binding failed.");
                $qryResultContainer->setFailure();
            }
            if (!$stmt->execute()){
                $qryResultContainer->addErrorMessage("Query execution failed.");
                $qryResultContainer->setFailure();
            };
            $result = $stmt->get_result();  
            $rows = $result->fetch_array();
            $this->close();
            if (COUNT($rows) > 0){
                return true;
            }else{
                return false;
            }
        }

        public function phoneExists($phone_number){
            $qryResultContainer = new ResultContainer();
            $sql = 'SELECT trainer_id
                        FROM Trainers
                        WHERE phone = ?;
            ';
            $stmt;
            if (!$stmt = $this->connect()->prepare($sql)){
                $qryResultContainer->addErrorMessage("Prepare statement failed.");
                $qryResultContainer->setFailure();
            }
            if (!$stmt->bind_param("s", $phone_number)){
                $qryResultContainer->addErrorMessage("Parameter binding failed.");
                $qryResultContainer->setFailure();
            }
            if (!$stmt->execute()){
                $qryResultContainer->addErrorMessage("Query execution failed.");
                $qryResultContainer->setFailure();
            };
            $result = $stmt->get_result();  
            $rows = $result->fetch_array();
            $this->close();
            if (COUNT($rows) > 0){
                return true;
            }else{
                return false;
            }
        }

        public function addUser($name, $phone_number, $email){
            $qryResultContainer = new ResultContainer();
            $sql = 'INSERT INTO Trainers (trainer_name, phone, email)
                        VALUE (?, ?, ?);
            ';
            $stmt;
            if (!$stmt = $this->connect()->prepare($sql)){
                $qryResultContainer->addErrorMessage("Prepare statement failed<br>");
                $qryResultContainer->setFailure();
            }
            if (!$stmt->bind_param("sss", $name, $phone_number, $email)){
                $qryResultContainer->addErrorMessage("Parameter binding failed<br>");
                $qryResultContainer->setFailure();
            }
            if (!$stmt->execute()){
                $qryResultContainer->addErrorMessage("Query execution failed<br>");
                $qryResultContainer->setFailure();
            };
            $this->close();
            return $qryResultContainer;
        }
    }
?>