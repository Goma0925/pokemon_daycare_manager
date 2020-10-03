<?php
    class InputErrorView { 
        public function errorBox($errorMessages){
            // Prints string in the given array.
            foreach ($errorMessages as $message){
                echo "<p style='color:red;'>".$message."</p>";
            }
        }
    }
?>