<?php
    include 'models/Database.php';
    class TrainersModel extends Database {
        public function getTrainersByName($name){
            $sql = 'SELECT * FROM Trainers WHERE trainer_id = ?';
            $stmt;
            if (!$stmt = $this->connect()->prepare("SELECT * FROM Trainers WHERE trainer_name = ?")){
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
    }
?>