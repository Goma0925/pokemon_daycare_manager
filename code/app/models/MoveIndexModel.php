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
    }
?>