<?php 
    class NotificationContr {
        private $notificationModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            $this->notificationModel = new NotificationModel();
        }

        public function addEggEvent($name, $eventDateTime, $parent1, $parent2){
            $result = new ResultContainer();

            //Input format validation.
            if (strlen($name) > 16){
                $result->setFailure();
                $result->addErrorMessage("Name has to be less than 16 characters.");
            }
            if (preg_match("/^[1-9]\d{2}-\d{3}-\d{4}/", $eventDateTime)){
                $result->setFailure();
                $result->addErrorMessage("Must be a valid date and time");
            }
            if ($parent1 == $parent2) {
                $result->setFailure();
                $result->addErrorMessage("Parent 1 and 2 can't be the same pokemon");


            }


            //Next, check for existance of trainer / pokemon trainer pairs
            $trainerExists = $this->notificationModel->trainerExists($name);
            if (!$trainerExists){
                $result->setFailure();
                $result->addErrorMessage("Trainer '".$name."' is not valid");
            }
            $parent1Exists = $this->notificationModel->trainerPokemonPairExists($name, $parent1);
            if (!$parent1Exists){
                $result->setFailure();
                $result->addErrorMessage("Parent 1 '".$parent1."' does not exists.");
            }

            $parent2Exists = $this->notificationModel->trainerPokemonPairExists($name, $parent2);
            if (!$parent2Exists){
                $result->setFailure();
                $result->addErrorMessage("Parent 2 '".$parent2."' does not exists.");
            }

            //If all validations pass, insert n egg event to the database.
            if ($result->isSuccess()){
                // get trainer id
                $trainerID = $this->notificationModel->getTrainerId($name);
               
                $realDateTime = str_replace("T", " ", $eventDateTime);

                // add notification
                
                $queryResult = $this->notificationModel->addNotification($trainerID, $realDateTime);
                if (!$queryResult->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult); //Retriving errors from model.
                };
                // get notification id
                $notifID = $this->notificationModel->getNotificationID($trainerID, $realDateTime);
                // add egg event
                $queryResult2 = $this->notificationModel->addEggEvent($notifID, $parent1, $parent2);
                if (!$queryResult2->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult2); //Retriving errors from model.
                };
              /*  $queryResult = $this->trainersModel->addUser($name, $phone_number, $email);
                if (!$queryResult->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult); //Retriving errors from model.
                };*/
                return $result;
            }else{
                return $result;
            }
        }
    }

?>