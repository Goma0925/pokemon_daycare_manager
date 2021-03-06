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

        4.  After returning from the function, use ResultContainer->is_success() to check if the operation was 
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
        private $error_messages;
        private $is_success;
        private $mysqli_result;
        private $success_values; //Used to carry any values caused by a successful operation.

        public function __construct() {
            $this->error_messages = Array();
            $this->success_values = Array();
            $this->is_success = true;
            $this->mysqli_result = null;
        }

        public function setSuccess(){
            $this->is_success = true;
        }

        public function setFailure(){
            $this->is_success = false;
        }

        public function isSuccess(){
            return $this->is_success;
        }

        public function setSuccessValue($key, $value){
            // Add a value you want to communicate about the successful operation.
            // It is stored in an associative array with key & value.
            $this->success_values[$key] = $value;
        }

        public function setSuccessValues($key, Array $values){
            // Add a value you want to communicate about the successful operation.
            // It is stored in an associative array with key & value.
            foreach ($values as $value){
                $this->success_values[$key] = $value;
            }
        }

        public function getSuccessValues(){
            return $this->success_values;
        }

        public function set_mysqli_result($mysqli_result){
            $this->mysqli_result = $mysqli_result;
        }

        public function get_mysqli_result(){
            return $this->mysqli_result;
        }

        public function addErrorMessage($message){
            $this->error_messages[] = $message;
        }

        public function getErrorMessages(){
            return $this->error_messages;
        }

        // quick fix for moment (should be fixed)
        // add array of messages to one container
        // public function mergeArrayErrorMessages($errMessages) { // do not wanna break your method below
        //     $this->error_messages = array_merge($this->error_messages, $errMessages);
        // }

        public function mergeErrorMessages($result_container){ 
            //Combine errorMessages and successValues of two ResultContainers.
            //$result_container: Another ResultContainer instance.
            $this->error_messages = array_merge($this->error_messages, $result_container->error_messages);
            $this->successValues = array_merge($this->success_values, $result_container->success_values);
            if (!$result_container->isSuccess()){
                $this->is_success = false;
            }
        }
    }
?>