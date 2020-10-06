<?php
    include_once 'models/Database.php';
    class NotificationModel extends Database {
        public function getAllNotifcations(){
            //This function returns all the trainer records that contain the name string
            //It ignores the distinction lowercase & uppercase.
            $sql = "SELECT n.date_created, 
                            t.trainer_name,
                            n.notification_id
                        FROM Notifications AS n
                        INNER JOIN (Trainers AS t)
                        ON (n.trainer_id = t.trainer_id);";
            $res_container = $this->handleQuery($sql); // positional or explicitly state
            return $res_container; 
        }

        public function getMoveNotifcations(){
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

            $res_container = $this->handleQuery($sql); // positional or explicitly state
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

            $res_container = $this->handleQuery($sql); // positional or explicitly state
            return $res_container; 
        }

        public function getEggNotifcations(){
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
            $res_container = $this->handleQuery($sql); // positional or explicitly state
            return $res_container; 
        }

      
    }
?>