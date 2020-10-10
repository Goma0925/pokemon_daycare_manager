<?php
    include_once 'utils/Query.php';
    class BusinessStatesModel { //Make sure to use plural noun for the class name

        // Always return ResultContainer to notify if the operation was successful + errors
        // ResultContainer(); //Custom error container. See utils/ResultContainer.php
        // We should probably always return $result(mysqli_result object) so that we can make all 
        // views following that coding rule.
        // Doc for mysqli_result object: https://www.php.net/manual/en/class.mysqli-result.php



        // direction 1 is from least to greatest
        // direct 0 is from greatest to least
        public function getBusinessStates($order_by_desc = false, $most_recent = false) {
            $query = new Query(); // NEW
            $sql = "SELECT * FROM BusinessStates"; 
            $query->addToSql($sql);
            if ($order_by_desc == true) {
                $query->addToSql(" ORDER BY date_changed DESC");
                if ($most_recent == true) {
                    $query->addToSql(" LIMIT 1");
                }
            }
            $query->addToSql(";");
            $resultContainer  = $query->handleQuery(); // returns ResultContainer
            return $resultContainer; // return type ResultContainer           
        }

        public function getCurrentBusinessState(){ // this can be accomplished with getBusinessStates above
            $query = new Query(); // NEW
            $sql = "SELECT * FROM BusinessStates ORDER BY date_changed DESC LIMIT 1;"; 
            $query->setSql($sql);
            $resultContainer  = $query->handleQuery(); // returns ResultContainer
            return $resultContainer; // return type ResultContainer
        }

        public function getAllBusinessStates() {
            $query = new Query(); // NEW
            $sql = "SELECT * FROM BusinessStates"; 
            $query->setSql($sql);
            $resultContainer  = $query->handleQuery(); // returns ResultContainer
            return $resultContainer; // return type ResultContainer           
        }

        public function addBusinessState($date_changed, $price_per_day, 
                                        $max_pokemon_per_trainer,
                                        $flat_egg_price) { 
            $query = new Query();
            $sql = "INSERT INTO BusinessStates(date_changed, price_per_day,
                                               max_pokemon_per_trainer, flat_egg_price)
                    VALUES(?, ?, ?, ?);"; 
            $query->setSql($sql);
            // echo $price_per_day;
            // echo $max_pokemon_per_trainer;
            // echo $flat_egg_price;
            $query->setBindArr([$date_changed, $price_per_day, 
            $max_pokemon_per_trainer,
            $flat_egg_price]);
            $query->setBindTypeStr("sdid");
            $resultContainer  = $query->handleQuery(); // returns ResultContainer
            return $resultContainer;
        }
    }
?>