<?php 
    include_once 'models/BusinessStatesModel.php';

    class BusinessStatesContr {
        private $businessStatesModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            //eg:  $this->trainersModel = new TrainersModel();
            $this->businessStatesModel = new BusinessStatesModel();
        }

        public function addNewBusinessState($date_changed = null, 
                                            $price_per_day = null, 
                                            $max_pokemon_per_trainer = null,
                                            $flat_egg_price = null) {
                

            if (isset($date_changed) || isset($price_per_day) || isset($max_pokemon_per_trainer) || isset($flat_egg_price)) {
                $currentState = $this->businessStatesModel->getCurrentBusinessState();
                $date_changed = str_replace("T"," ",$date_changed);
                if ($currentState->isSuccess() && $currentState->get_mysqli_result()->num_rows == 1) {
                    $attr = $currentState->get_mysqli_result();
                    $date_changed = isset($date_changed) ? 
                        $date_changed : $attr["date_changed"];
                    $price_per_day = isset($price_per_day) ? 
                        $price_per_day : $attr["price_per_day"];
                    $max_pokemon_per_trainer = isset($max_pokemon_per_trainer) ?
                        $max_pokemon_per_trainer : $attr["max_pokemon_per_trainer"];
                    $flat_egg_price = isset($flat_egg_price) ?
                        $flat_egg_price : $attr["flat_egg_price"];
                    $currentState = $this->businessStatesModel->addBusinessState($date_changed,$price_per_day,
                        $max_pokemon_per_trainer, $flat_egg_price);
                    var_dump($currentState->get_mysqli_result());
                    return true;
                }
            } 
            else {
                return false;
                echo "Submit pressed, but no new values entered!";
            }

        } // function end
    } // class end
?>