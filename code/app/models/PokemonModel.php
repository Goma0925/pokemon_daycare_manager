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
                    nickname, breedname
                    FROM ActivePokemon WHERE pokemon_id = ?";
            $bindTypeStr = "i";
            $bindArr = [$pokemon_id];
            $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            return $result;
        }

        // https://www.php.net/manual/en/function.isset.php - null checking (defaults for us)
        // https://www.php.net/manual/en/functions.arguments.php - default args
        public function getPokemonByTrainer(int $trainer_id = null, 
                                            string $phone = null, 
                                            string $email = null) {
            // Account for following errors:  
                // Bad phone number form
                // Bad email form                                 
                // Trainer dne (actually might wait on doing this; 
                // requires some intermediate queries.) 
            /** Loop over all provided to see if any succeed */
            $arg_list = [$trainer_id, $phone, $email];
            $result;
            if (isset($trainer_id)) {
                // try
                $sql = "SELECT pokemon_id, trainer_id, current_level,
                nickname, breedname
                FROM ActivePokemon WHERE trainer_id = ?";
                $bindTypeStr = "i";
                $bindArr = [$trainer_id];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }
            if (isset($phone) && !$result) {
                // try
                $sql = "SELECT pokemon_id, trainer_id, current_level,
                nickname, breedname
                FROM ActivePokemon INNER JOIN USING (phone) WHERE phone = ?";
                $bindTypeStr = "s";
                $bindArr = [$phone];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }
            if (isset($email) && !$result) {
                // try
                $sql = "SELECT pokemon_id, trainer_id, current_level,
                nickname, breedname
                FROM ActivePokemon INNER JOIN USING (email) WHERE email = ?";
                $bindTypeStr = "s";
                $bindArr = [$email];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }
            if(!$result) { // do this here or handle elsewhere? maybe in view instead
                echo "No trainer with this information has an active pokemon.";
            }
            return $result; 
        }
    }
?>