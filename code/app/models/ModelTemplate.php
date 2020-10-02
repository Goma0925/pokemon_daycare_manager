<?php
    include 'models/Database.php';
    class SomethingsModel extends Database { //Make sure to use plural noun for the class name
        public function someMethod(){
            $sql = //SQL for prepared statement;
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
            $result = $stmt->get_result(); //Get mysqli_result object.

            //We should probably always return $result(mysqli_result object) so that we can make all 
            // views following that coding rule.
            // Doc for mysqli_result object: https://www.php.net/manual/en/class.mysqli-result.php
            return $result;
        }
    }
?>