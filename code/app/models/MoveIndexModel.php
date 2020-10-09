<?php
    include_once 'models/PokemonModel.php';
    class MoveIndexModel { 

        public function getMoveIndex() {
            $query = new Query();
            $sql = "SELECT * FROM Moves;";
            $query->setSql($sql);
            $resultContainer  = $query->handleQuery(); // other args are optional, read handleQuery
            return $resultContainer ;
        }

        public function getCurrentMovesofPokemon($id){
            $query = new Query();
            $sql = "SELECT * 
                    FROM CurrentMoves
                    WHERE pokemon_id = ?;";

            $bindTypeStr = "i"; 
            $bindArr = Array($id);
            $query->setAll($sql,$bindTypeStr,$bindArr);
            $resultContainer = $query->handleQuery();   
            return $resultContainer ;


        }


    }
?>