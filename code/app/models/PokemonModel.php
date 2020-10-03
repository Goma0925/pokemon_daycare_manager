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
    }
?>