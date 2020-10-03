<?php
    include 'models/Database.php';
    class SomethingsModel extends Database { //Make sure to use plural noun for the class name
        public function someSelectMethod(){
            $qryResult = new ResultContainer(); //Custom error container. See utils/ResultContainer.php
            $sql = //SQL for prepared statement;
            $stmt;
            if (!$stmt = $this->connect()->prepare($sql)){
                $qryResult->addErrorMessage("Prepare statement failed.");
                $qryResult->setFailure();
            }
            if (!$stmt->bind_param("sss", $name, $phone_number, $email)){
                $qryResult->addErrorMessage("Parameter binding failed.");
                $qryResult->setFailure();
            }
            if (!$stmt->execute()){
                $qryResult->addErrorMessage("Query execution failed.");
                $qryResult->setFailure();
            };

            if ($qryResult->isSuccess()){
                $result = $stmt->get_result(); //Get mysqli_result object.
            }

            //We should probably always return $result(mysqli_result object) so that we can make all 
            // views following that coding rule.
            // Doc for mysqli_result object: https://www.php.net/manual/en/class.mysqli-result.php
            return $result;
        }

        public function someInsertAndDeleteFunction(){
            $qryResult = new ResultContainer(); //Custom error container. See utils/ResultContainer.php
            $sql = //SQL for prepared statement;
            $stmt;
            if (!$stmt = $this->connect()->prepare($sql)){
                $qryResult->addErrorMessage("Prepare statement failed.");
                $qryResult->setFailure();
            }
            if (!$stmt->bind_param("sss", $name, $phone_number, $email)){
                $qryResult->addErrorMessage("Parameter binding failed.");
                $qryResult->setFailure();
            }
            if (!$stmt->execute()){
                $qryResult->addErrorMessage("Query execution failed.");
                $qryResult->setFailure();
            };

            //Always return ResultContainer to notify if the operation was successful + errors
            return $qryResult;
        }
    }
?>