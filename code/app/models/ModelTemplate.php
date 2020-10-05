<?php
    include_once 'models/Database.php';
    class SomethingsModel extends Database { //Make sure to use plural noun for the class name

        // Always return ResultContainer to notify if the operation was successful + errors
        // ResultContainer(); //Custom error container. See utils/ResultContainer.php
        // We should probably always return $result(mysqli_result object) so that we can make all 
        // views following that coding rule.
        // Doc for mysqli_result object: https://www.php.net/manual/en/class.mysqli-result.php

        public function someMethod(){
            $sql = "..."; // some query
            $resultContainer  = $this->handleQuery($sql); // returns ResultContainer
            return $resultContainer; // return type ResultContainer
        }
    }
?>