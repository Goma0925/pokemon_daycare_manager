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

            if (!is_null($price_per_day) && !is_null($max_pokemon_per_trainer) && !is_null($flat_egg_price)) {
                $resultContainer = $this->businessStatesModel->addBusinessState($date_changed,$price_per_day,
                    $max_pokemon_per_trainer, $flat_egg_price);
                $resultContainer->setSuccessValue("bstate","Business state updated!");
                return $resultContainer;
            } 
            else {
                $resultContainer = new ResultContainer();
                $resultContainer->setFailure();
                $resultContainer->addErrorMessage("All fields cannot be left blank!");
                return $resultContainer;
            }

        } // function end
    } // class end
?>