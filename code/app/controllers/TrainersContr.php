<?php 
    class TrainersContr {
        private $trainersModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            $this->trainersModel = new TrainersModel();
        }

        public function addTrainer($name, $phone_number, $email){
            $result = new ResultContainer();

            //Input format validation.
            if (strlen($name) > 16){
                $result->setFailure();
                $result->addErrorMessage("Name has to be less than 16 characters.");
            }
            if (strlen($phone_number) > 12 && preg_match("/^[1-9]\d{2}-\d{3}-\d{4}/", $phone_number)){
                $result->setFailure();
                $result->addErrorMessage("Phone number has to be less than 12 characters and follow the format XXX-XXX-XXXX.");
            }
            if (strlen($email) > 25 && preg_match("/([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})/", $email)){
                $result->setFailure();
                $result->addErrorMessage("Email has to be less than 25 characters and a valid email address");
            }

            //Next, check for duplicate email and phone in the database.
            $emailExists = $this->trainersModel->emailExists($email);
            if ($emailExists){
                $result->setFailure();
                $result->addErrorMessage("Email '".$email."' is already taken.");
            }
            $phoneExists = $this->trainersModel->phoneExists($phone_number);
            if ($phoneExists){
                $result->setFailure();
                $result->addErrorMessage("Phone number '".$phone_number."' is already taken.");
            }

            //If all validations pass, insert a trainer to the database.
            if ($result->isSuccess()){
                $queryResult = $this->trainersModel->addUser($name, $phone_number, $email);
                if (!$queryResult->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult); //Retriving errors from model.
                };
                return $result;
            }else{
                return $result;
            }
        }
    }

?>