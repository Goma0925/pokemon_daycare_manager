<?php 
    include_once 'models/MoveIndexModel.php';


    class NotificationContr {
        private $notificationModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            $this->notificationModel = new NotificationModel();
        }



        // adding an egg event
        public function addEggEvent($eventDateTime, $parent1, $parent2){
            $result = new ResultContainer();

            //Input format validation.

            if (preg_match("/^[1-9]\d{2}-\d{3}-\d{4}/", $eventDateTime)){
                $result->setFailure();
                $result->addErrorMessage("Must be a valid date and time");
            }
            if ($parent1 == $parent2) {
                $result->setFailure();
                $result->addErrorMessage("Parent 1 and 2 can't be the same pokemon");
            }



            //Next, check for existance of trainer / pokemon trainer pairs
            $parent1TrainerID = $this->notificationModel->getTrainerIdByPokemon($parent1);
            $parent2TrainerID = $this->notificationModel->getTrainerIdByPokemon($parent2);

            if ($parent1TrainerID != $parent2TrainerID) {
                $result->setFailure();
                $result->addErrorMessage("The trainer for the two pokemon are not the same");
            }


            $realDateTime = str_replace("T", " ", $eventDateTime);
            $trainerID = $this->notificationModel->getTrainerIdByPokemon($parent2);
            
            $eggexists = $this->notificationModel->eggeventExists($trainerID);
            if ($eggexists) {
                $result->setFailure();
                $result->addErrorMessage("Cant have 2 eggs for same trainer at same time");
            }


            //If all validations pass, insert an egg event to the database.
            if ($result->isSuccess()){
                // get trainer id
                $trainerID = $this->notificationModel->getTrainerIdByPokemon($parent2);
               
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

                return $result;
            }else{
                return $result;
            }
        }


        // adding an move event
        public function addMoveEvent($eventDateTime, $pokemonID, $oldMove, $newMove){
            $result = new ResultContainer();
            $insertOrDelete = false;
            //Input format validation.

            if (preg_match("/^[1-9]\d{2}-\d{3}-\d{4}/", $eventDateTime)){
                $result->setFailure();
                $result->addErrorMessage("Must be a valid date and time");
            }
            if ($oldMove == $newMove) {
                $result->setFailure();
                $result->addErrorMessage("Moves cannot be the same");
            }
            

            //validation for the purposes of four or less and duplicate moves
            $moveIndexModel = new MoveIndexModel();
            $resultContainer = $moveIndexModel->getCurrentMovesofPokemon($pokemonID);
            $poke_num = $resultContainer->get_mysqli_result()->num_rows;
            $rows_num = $resultContainer->get_mysqli_result()->fetch_all();
            if ($rows_num != null){
                for ($i=0; $i<$poke_num; $i++){
                    if ( $rows_num[$i][0] == $newMove){

                        $result->setFailure();
                        $result->addErrorMessage("Moves cannot be the same");

                    }
                }
            }

            if ($poke_num < 4) {

                $insertOrDelete = true;

            }
            
            // need to make it int because reasons
            $pokemonIDInt = (int) $pokemonID;
        

            //If all validations pass, insert an egg event to the database.
            if ($result->isSuccess()){

                // get trainer id
                $trainerID = $this->notificationModel->getTrainerIdByPokemon($pokemonIDInt);
               
                $realDateTime = str_replace("T", " ", $eventDateTime);
                $queryResult = $this->notificationModel->addNotification($trainerID, $realDateTime);
                if (!$queryResult->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult); //Retriving errors from model.
                };

            // conditional whether it is an insert of a delete
            if ($insertOrDelete == false) {
                $queryResult3 = $this->notificationModel->updateCurrentMoves($pokemonIDInt, $oldMove, $newMove);
                if (!$queryResult3->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult3); //Retriving errors from model.
                    
                };

                 // get notification id
                $trainerID = $this->notificationModel->getTrainerIdByPokemon($pokemonIDInt);
                $notifID = $this->notificationModel->getNotificationID($trainerID, $realDateTime);
                // add fight event
                $queryResult2 = $this->notificationModel->addMoveEvent($notifID, $oldMove, $newMove, $pokemonIDInt);
                if (!$queryResult2->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult2); //Retriving errors from model.
                };
            }
            else {

                $queryResult4 = $this->notificationModel->insertCurrentMoves($pokemonIDInt,$newMove);
                if (!$queryResult4->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult4); //Retriving errors from model.
                };
                 // get notification id
                 $trainerID = $this->notificationModel->getTrainerIdByPokemon($pokemonIDInt);
                 $notifID = $this->notificationModel->getNotificationID($trainerID, $realDateTime);
                 // add fight event
                 $queryResult2 = $this->notificationModel->addMoveEvent($notifID, NULL, $newMove, $pokemonIDInt);
                 if (!$queryResult2->isSuccess()){
                     $result->setFailure();
                     $result->mergeErrorMessages($queryResult2); //Retriving errors from model.
                 };


            }


                


                return $result;
            }else{
                return $result;
            }
        }


        // adding a fight event
        public function addFightEvent($pokemonID, $description, $eventDateTime){
            $result = new ResultContainer();

            //Input format validation.

            if (preg_match("/^[1-9]\d{2}-\d{3}-\d{4}/", $eventDateTime)){
                $result->setFailure();
                $result->addErrorMessage("Must be a valid date and time");
            }
            if (strlen($description) > 500){
                $result->setFailure();
                $result->addErrorMessage("Description must be less than 500 characters");
            }



            //If all validations pass, insert an Fight event to the database.
            if ($result->isSuccess()){
                // get trainer id
                $trainerID = $this->notificationModel->getTrainerIdByPokemon($pokemonID);
               
                $realDateTime = str_replace("T", " ", $eventDateTime);

                // add notification
                
                $queryResult = $this->notificationModel->addNotification($trainerID, $realDateTime);
                if (!$queryResult->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult); //Retriving errors from model.
                };
                
                $queryResult3 = $this->notificationModel->addFight($description);
                if (!$queryResult3->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult3); //Retriving errors from model.
                };


                // get notification id
                $notifID = $this->notificationModel->getNotificationID($trainerID, $realDateTime);
                $fightID = $this->notificationModel->getFightID($description);
                // add fight event
                $queryResult2 = $this->notificationModel->addFightEvent($notifID, $pokemonID, $fightID);
                if (!$queryResult2->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult2); //Retriving errors from model.
                };

                return $result;
            }else{
                return $result;
            }
        }


        public function deleteNotification($notifID) {
            $result = new ResultContainer();

                $queryResult = $this->notificationModel->deleteNotification($notifID);

                if (!$queryResult->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult); //Retriving errors from model.
                };

                return $result;

        }


        public function updateEgg($notifID) {
            $result = new ResultContainer();

                $queryResult = $this->notificationModel->updateEgg($notifID);

                if (!$queryResult->isSuccess()){
                    $result->setFailure();
                    $result->mergeErrorMessages($queryResult); //Retriving errors from model.
                };

                return $result;

        }








    }

?>