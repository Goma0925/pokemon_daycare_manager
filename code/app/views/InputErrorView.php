<?php
    class InputErrorView { 
        public function errorBox($errorMessages){
            // Prints string in the given array.
            foreach ($errorMessages as $message){
                echo 
                '
                <div class="alert alert-danger" role="alert">
                    '.$message.'
                </div>
                ';
            }
        }
    }
?>