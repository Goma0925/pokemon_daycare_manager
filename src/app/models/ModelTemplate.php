<?php
    include_once 'utils/Query.php'; // NEW
 
    class SomethingsModel { //Make sure to use plural noun for the class name

        // Always return ResultContainer to notify if the operation was successful + errors
        // ResultContainer(); //Custom error container. See utils/ResultContainer.php
        // We should probably always return $result(mysqli_result object) so that we can make all 
        // views following that coding rule.
        // Doc for mysqli_result object: https://www.php.net/manual/en/class.mysqli-result.php


        // *** READ utils/Query.php to see how to construct queries *** 
        public function someMethod(){
            $query = new Query(); // NEW
            $sql = "..."; // some query
            // $bindArr = [some arguments to bind to prepared stmt];
            // $bindTypeStr = "types of arguments to bind"; // parallel to bindArr
            $resultContainer  = $query->handleQuery(); // returns ResultContainer
            return $resultContainer; // return type ResultContainer
        }
    }
?>