<?php
    include 'models/Database.php';
    class PokemonModel extends Database { 

        public function getPokemonColNames() {
            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Pokemon'";
            $result = $this->handleQuery($sql); // other args are optional
            return $result;
        } 

        public function getPokemonByID($pokemon_id) {
            /* Prepare args for query handler */
            $sql = "SELECT pokemon_id, trainer_id, current_level,
                    nickname, breedname
                    FROM Pokemon WHERE pokemon_id = ?";
            $bindTypeStr = "i";
            $bindArr = [$pokemon_id];
            $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            return $result;
        }

        public function getAllActivePokemon() {
            /* Prepare args for query handler */
            $sql = "SELECT pokemon_id, trainer_id, current_level,
                    nickname, breedname FROM ActivePokemon";
            $result = $this->handleQuery($sql);
            return $result;
        }


        public function getPokemonByTrainer(int $trainer_id = null, 
                                            string $phone = null, 
                                            string $email = null) {                      
            // https://www.php.net/manual/en/functions.arguments.php - default args

            /**  Account for following errors:  
                * Bad phone number form
                * Bad email form                                 
                * Trainer dne (actually might wait on doing this; 
                * requires some intermediate queries. Right now
                * we just determine if any active pokemon with that
                * trainer info exist, which is sufficient.) 
            */
            
            // Preparing args for query handler calls
            $arg_list = [$trainer_id, $phone, $email];
            $result;
            $base_sql = "SELECT pokemon_id, trainer_id, current_level,
            nickname, breedname FROM ActivePokemon"; 

            /** Go over all non-null, set args provided to see if any succeed.
                * isset(): https://www.php.net/manual/en/function.isset.php
             * These are not in loop because binding varies
             * for each type. 
             * Be wary of using mysqli_num_rows for large queries:
                *  https://www.php.net/manual/de/mysqlinfo.concepts.buffering.php
                *  https://stackoverflow.com/questions/35820810/php-get-result-returns-an-empty-result-set
            */
            if (isset($trainer_id)) {
                // try
                $sql = $base_sql."WHERE trainer_id = ?";
                $bindTypeStr = "i";
                $bindArr = [$trainer_id];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr); // return false if failed.
            }
            if (isset($phone) && mysqli_num_rows($result) == 0) {
                // previous failed, try this
                $sql = $base_sql."INNER JOIN USING (phone) WHERE phone = ?";
                $bindTypeStr = "s";
                $bindArr = [$phone];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }
            if (isset($email) && mysqli_num_rows($result) == 0) {
                // previous failed, try this
                $sql = $base_sql."INNER JOIN USING (email) WHERE email = ?";
                $bindTypeStr = "s";
                $bindArr = [$email];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }
            return $result; 
        }
    }
?>