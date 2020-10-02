<?php 
    class TrainersContr {
        private $trainersModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            $this->trainersModel = new TrainersModel();
        }
    }

?>