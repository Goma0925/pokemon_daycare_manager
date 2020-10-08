<?php 
    include_once 'models/PokemonModel.php';

    class PokemonContr {//Make sure to use plural noun for the class nam
        private $pokemonModel;
        public function __construct() {
            $this->pokemonModel = new PokemonModel();
        }

        public function addPokemon($trainer_id, $level, $nickname, $breedname, $move_names){
            $resultAddPokemon = new ResultContainer();
            if (count($move_names) < 2){
                $resultAddPokemon->setFailure();
                $resultAddPokemon->addErrorMessage("Pokemon must have at least two moves");
            }else{
                //Pre-aquire the next pokemon id
                $resultPokemonId = $this->pokemonModel->getNextPokemonId();
                $pokemon_record = $resultPokemonId ->get_mysqli_result()->fetch_assoc();
                $pokemon_id = $pokemon_record? $pokemon_record["AUTO_INCREMENT"]: "-1";

                //Add the pokemon
                $resultAddPokemon = $this->pokemonModel->addPokemon($trainer_id, $level, $nickname, $breedname);
                if ($resultAddPokemon->isSuccess()){
                    //Get the pokemon you just added 
                    echo "Just added:".$pokemon_id;
                    $resultAddCurrMove = $this->pokemonModel->addCurrentMoves($pokemon_id, $move_names);
    
                    if (!$resultAddCurrMove->isSuccess()){
                        $resultAddPokemon->mergeErrorMessages($resultAddCurrMove);
                    }
                }
            }

            return $resultAddPokemon;
        }
    }

?>