<?php 
    include_once 'models/PokemonModel.php';

    class PokemonContr {//Make sure to use plural noun for the class nam
        private $pokemonModel;
        public function __construct() {
            $this->pokemonModel = new PokemonModel();
        }

        public function addPokemon($trainer_id, $level, $nickname, $breedname, $move_names){
            $resultAddPokemon = new ResultContainer();

            //Check if moves are valid
            if (count($move_names) < 2){
                //Check if more than one move is submitted.
                $resultAddPokemon->setFailure();
                $resultAddPokemon->addErrorMessage("Pokemon must have at least two moves");
            }
            if (count(array_unique($move_names)) != count($move_names)){
                //Check there are no duplicate moves submitted.
                $resultAddPokemon->setFailure();
                $resultAddPokemon->addErrorMessage("Pokemon cannot have duplicate moves.");
            }
            
            if ($resultAddPokemon->isSuccess()){
                //Move validation success. Now add the pokemon.
                //Pre-aquire the next pokemon id
                $resultPokemonId = $this->pokemonModel->getNextPokemonId();
                $pokemon_record = $resultPokemonId ->get_mysqli_result()->fetch_assoc();
                $pokemon_id = $pokemon_record? $pokemon_record["AUTO_INCREMENT"]: "-1";

                //Add the pokemon
                $resultAddPokemon = $this->pokemonModel->addPokemon($trainer_id, $level, $nickname, $breedname);
                if ($resultAddPokemon->isSuccess()){
                    //Get the pokemon you just added 
                    $resultAddPokemon->setSuccessValue("pokemon_id", $pokemon_id);
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