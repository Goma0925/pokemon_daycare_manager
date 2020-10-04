<!-- This class is a helper script to communicate error details and 
    the status of operation(success/failure)
    It can be used between Controller & Model or View & Model class's

    What ResultContainer does
    1. Return success or failure (boolean success flag)
    2. An array of error messages, mainly for display to end users.
    3. mysqli_result object which contains the SQL query result.

    How to use it:
        1.  Instantiate in a function where you want to return error messsages and success flag

        2a. If you're executing SELECT query, you can add the $result(mysqli_result object)
            to ResultContainer by ResultContainer->set_mysqli_result().

        2b. Use ResultContainer->setFailure() to mark the success flag false 
            to communicate the operation failure

        3.  Use ResultContainer->addErrorMessage($message) to add an error message to return. 
            Multiple error messages can be added. Note these error messages
            are intended for end user. Do not add technical errror message.

        4.  After returning from the function, use ResultContainer->isSuccess() to check if the operation was 
            successful (The default success flag is true, so you could skip this).

        5.  Once ResultContainer is returned to Controller or View, you can check the success by 
            calling ResultContainer.isSuccess(), which returns true if success.

        6a. If the operation is successful, you can get the query result by 
            using ResultContainer->set_mysqli_result() which returns mysqli_result object.
        6b. If the operation failed, use ResultContainer->getErrorMessages() to retrieve an array of 
            error messages.

        *   If you want to merge the error messages from two ResultContainer objects, use
            ResultContainer->mergeError()


    We can use this more. We currently
    never use it beyond scope of class
    methods. 

-->

<?php 
    class ResultContainer{
        private $errorMessages;
        private $isSuccess;
        private $mysqli_result;

        public function __construct() {
            $this->errorMessages = Array();
            $this->isSuccess = true;
            $this->mysqli_result = null;
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

        public function set_mysqli_result($mysqli_result){
            $this->mysqli_result = $mysqli_result;
        }

        public function get_mysqli_result(){
            return $this->mysqli_result;
        }

        public function addErrorMessage($message){
            $this->errorMessages[] = $message;
        }

        public function getErrorMessages(){
            return $this->errorMessages;
        }

        public function mergeErrorMessages($resultContainer){
            //$resultContainer: Another ResultContainer instance.
            $this->errorMessages = array_merge($this->errorMessages, $resultContainer->errorMessages);
        }
    }
?>