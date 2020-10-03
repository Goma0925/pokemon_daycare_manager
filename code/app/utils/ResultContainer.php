<!-- This class is a helper script to return two things fron Controller or View class's function
    1. Return success or failure
    2. An array of error messages.

    How to use it:
        1. Instantiate in a function whre you want to return error messsages and success flag
        2. Use ResultContainer->setFailure() to mark the success flag false 
            to communicate the operation failure
        3. Use ResultContainer->addErrorMessage($message) to add an error message to return. Multiple error
            messages can be added.
        4. After returning from the function, use ResultContainer->isSuccess() to check if the operation was 
            successful
        5. If the operation failed, use ResultContainer->getErrorMessages() to retrieve an array of 
            error messages.

        *If you want to merge the error messages from two ResultContainer objects, use
            ResultContainer->mergeError()
-->

<?php 
    class ResultContainer{
        private $errorMessages;
        private $isSuccess;
        public function __construct() {
            $this->errorMessages = Array();
            $this->isSuccess = true;
        }

        public function setSuccess(){
            $this->isSuccess = true;
        }

        public function setFailure(){
            $this->isSuccess = false;
        }

        public function isSuccess(){
            return $this->isSuccess;
        }

        public function addErrorMessage($message){
            $this->errorMessages[] = $message;
        }

        public function getErrorMessages(){
            return $this->errorMessages;
        }

        public function mergeErrorMessages($resultContainer){
            //$resultContainer: Another ResultContainer instance
            $this->errorMessages = array_merge($this->errorMessages, $resultContainer->errorMessages);
        }
    }
?>