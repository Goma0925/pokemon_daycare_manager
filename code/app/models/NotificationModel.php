<?php
    include_once 'models/Database.php';
    include_once 'utils/Query.php';

    class NotificationModel extends Database {
        
        // start of query functions
        public function getAllNotifcations(){
            $query = new Query(); // ADDED

            //This function returns all the trainer records that contain the name string
            //It ignores the distinction lowercase & uppercase.
            $sql = "SELECT n.date_created, 
                            t.trainer_name,
                            n.notification_id
                        FROM Notifications AS n
                        INNER JOIN (Trainers AS t)
                        ON (n.trainer_id = t.trainer_id);";

            $query->setSql($sql); // ADDED

            $res_container = $query->handleQuery(); // ADDED

            return $res_container; 
        }

        // start of "get stuff from database" methods
        public function getMoveNotifcations(){
            $query = new Query(); // ADDED

            //This function returns all the trainer records that contain the name string
            //It ignores the distinction lowercase & uppercase.
            $sql = "SELECT Notifications.date_created,
                            m.notification_id, 
                            p.breedname,
                            m.old_move_name,
                            m.new_move_name,
                            Trainers.trainer_name  
                            FROM MoveEvents AS m
                            INNER JOIN (Notifications) 
                            ON (m.notification_id = Notifications.notification_id)
                            INNER JOIN (Pokemon AS p)
                            ON (p.pokemon_id = m.pokemon_id)
                            INNER JOIN (Trainers)
                            ON (Trainers.trainer_id = Notifications.trainer_id);";
            $query->setSql($sql); // ADDED
            $res_container = $query->handleQuery(); // ADDED

            return $res_container; 
        }


        public function getFightNotifcations(){
            //This function returns all the trainer records that contain the name string
            //It ignores the distinction lowercase & uppercase.
            $sql = "SELECT Notifications.date_created, 
                            Notifications.notification_id,
                            p.breedname,
                            f1.fight_description,
                            Trainers.trainer_name 
                        FROM FightEvents AS f
                        INNER JOIN (Notifications)
                        ON (f.notification_id = Notifications.notification_id)
                        INNER JOIN (Trainers)
                        ON (Trainers.trainer_id = Notifications.trainer_id)
                        INNER JOIN (Pokemon AS p)
                        ON (p.pokemon_id = f.pokemon_id)
                        INNER JOIN (Fights AS f1)
                        ON (f.fight_id = f1.fight_id);";


            $query = new Query(); // ADDED


            $query->setSql($sql); // ADDED

            $res_container = $query->handleQuery(); // ADDED

            return $res_container; 
        }


        public function getEggNotifcations(){
            $query = new Query();
            //This function returns all the trainer records that contain the name string
            //It ignores the distinction lowercase & uppercase.
            $sql = "SELECT Notifications.date_created, 
                            e.notification_id,
                            p1.breedname AS parent1,
                            p2.breedname AS parent2,
                            Trainers.trainer_name  
                            FROM EggEvents AS e
                     INNER JOIN (Notifications) 
                    ON (e.notification_id = Notifications.notification_id)
                    INNER JOIN (Pokemon AS p1)
                    ON (p1.pokemon_id = e.father)
                    INNER JOIN (Pokemon AS p2)
                    ON (p2.pokemon_id = e.mother)
                    INNER JOIN (Trainers)
                    ON (Trainers.trainer_id = Notifications.trainer_id);";

            $query->setSql($sql); // ADDED

            $res_container = $query->handleQuery(); // ADDED
            
            return $res_container; 
        }


        // get a trainer_id from trainer_name
        public function getTrainerId($name){
            $query = new Query();
            $sql = "SELECT trainer_id
                        FROM Trainers
                        WHERE trainer_name = ?;";
            //Construct bind parameters
            $bindTypeStr = "s"; 
            $bindArr = Array($name);
            $query->setAll($sql,$bindTypeStr,$bindArr);
            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();                
            if (!$resultContainer->isSuccess()){
                $result->setFailure();
                $result->mergeErrorMessages($queryResult); //Retriving errors from model.
            };
            $row = $resultContainer->get_mysqli_result()->fetch_assoc();
            

            return $row["trainer_id"];
        }


        // used to get a trainer_id from a pokemon_id
        public function getTrainerIdByPokemon($pokemonID){
            $query = new Query();
            $sql = "SELECT trainer_id
                        FROM Pokemon
                        WHERE pokemon_id = ?;";
            //Construct bind parameters
            $bindTypeStr = "s"; 
            $bindArr = Array($pokemonID);
            $query->setAll($sql,$bindTypeStr,$bindArr);
            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();                
            if (!$resultContainer->isSuccess()){
                $result->setFailure();
                $result->mergeErrorMessages($queryResult); //Retriving errors from model.
            };
            $row = $resultContainer->get_mysqli_result()->fetch_assoc();
            

            return $row["trainer_id"];
        }


        // used to get a notiifcation_id off of a trainer_id and a date_created
        public function getNotificationId($notif_id, $datetime){
            $query = new Query();
            $sql = "SELECT notification_id
                        FROM Notifications
                        WHERE trainer_id = ? && date_created = ?;";
            //Construct bind parameters
            $bindTypeStr = "is"; 
            $bindArr = Array($notif_id, $datetime);
            $query->setAll($sql,$bindTypeStr,$bindArr);
            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();                
            if (!$resultContainer->isSuccess()){
                $result->setFailure();
                $result->mergeErrorMessages($queryResult); //Retriving errors from model.
            };
            $row = $resultContainer->get_mysqli_result()->fetch_assoc();
            

            return $row["notification_id"];
        }


        // used to get a fight_id off of a fight_description
        public function getFightId($description){
            $query = new Query();
            $sql = "SELECT fight_id
                        FROM Fights
                        WHERE fight_description = ?;";
            //Construct bind parameters
            $bindTypeStr = "s"; 
            $bindArr = Array($description);
            $query->setAll($sql,$bindTypeStr,$bindArr);
            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();                
            if (!$resultContainer->isSuccess()){
                $result->setFailure();
                $result->mergeErrorMessages($queryResult); //Retriving errors from model.
            };
            $row = $resultContainer->get_mysqli_result()->fetch_assoc();
            

            return $row["fight_id"];
        }










        /* start of adding notification */

        // add general notification
        public function addNotification($trainerID, $dateTime){
            $query = new Query();
            $sql = 'INSERT INTO Notifications (trainer_id, date_created)
                        VALUE (?, ?);
            ';
            //Construct bind parameters
            $bindTypeStr = "is"; 
            $bindArr = Array($trainerID, $dateTime);
            $query->setAll($sql,$bindTypeStr,$bindArr);

            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();

            //Return the result container that contains a success flag and mysqli_result.
            return $resultContainer;
        }

        // adding to Fights table
        public function addFight($description){
            $query = new Query();
            $sql = 'INSERT INTO Fights (fight_description)
                        VALUE (?);
            ';
            //Construct bind parameters
            $bindTypeStr = "s"; 
            $bindArr = Array($description);
            $query->setAll($sql,$bindTypeStr,$bindArr);

            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();

            //Return the result container that contains a success flag and mysqli_result.
            return $resultContainer;
        }

        // adding to EggEvents table
        public function addEggEvent($notifID, $parent1, $parent2){
            $query = new Query();
            $sql = 'INSERT INTO EggEvents (notification_id, father, mother)
                        VALUE (?, ?, ?);
            ';
            //Construct bind parameters
            $bindTypeStr = "iii"; 
            $bindArr = Array($notifID, $parent1, $parent2);
            $query->setAll($sql,$bindTypeStr,$bindArr);

            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();

            //Return the result container that contains a success flag and mysqli_result.
            return $resultContainer;
        }

        // adding to FightEvents Table
        public function addFightEvent($notifID, $pokemonID, $fightID){
            $query = new Query();
            $sql = 'INSERT INTO FightEvents (notification_id, pokemon_id, fight_id)
                        VALUE (?, ?, ?);
            ';
            //Construct bind parameters
            $bindTypeStr = "iii"; 
            $bindArr = Array($notifID, $pokemonID, $fightID);
            $query->setAll($sql,$bindTypeStr,$bindArr);

            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();

            //Return the result container that contains a success flag and mysqli_result.
            return $resultContainer;
        }

/*
        
        
        // check trainer exists from trainer_name
        public function trainerExists($name){
            $query = new Query();
            $sql = "SELECT trainer_id
                        FROM Trainers
                        WHERE trainer_name = ?;";
            //Construct bind parameters
            $bindTypeStr = "s"; 
            $bindArr = Array($name);
            $query->setAll($sql,$bindTypeStr,$bindArr);
            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();

            //Get the number of rows to check if the record with the email exsits.
            $result = $resultContainer->get_mysqli_result();  
            $row_num = $result->num_rows;

            //Return true if the record with the email exsits.
            if ($row_num > 0){
                return true;
            }else{
                return false;
            }
        }

        // check trainer/pokemon pair exists from trainer_name and 
        public function trainerPokemonPairExists($name, $pokemon){
            $query = new Query();
            $sql = "SELECT Trainers.trainer_id, Pokemon.pokemon_id
                        FROM Trainers
                        INNER JOIN (Pokemon)
                        ON (Trainers.trainer_id = Pokemon.trainer_id)
                        WHERE trainer_name = ?;";
            //Construct bind parameters
            $bindTypeStr = "s"; 
            $bindArr = Array($name);
            $query->setAll($sql,$bindTypeStr,$bindArr);
            //Send query to database. Refer to utils/ResultContainer.php for its contents.
            $resultContainer = $query->handleQuery();

            //Get the number of rows to check if the record with the email exsits.
            while ($row = $resultContainer->get_mysqli_result()->fetch_assoc()) {

                if ($row["pokemon_id"] == $pokemon) {
                    return true;

                }


            }
            return false;

        }

*/


    }
?>