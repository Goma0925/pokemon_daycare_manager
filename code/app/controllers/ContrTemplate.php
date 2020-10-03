<?php 
    include 'models/setModelClassNameHere.php';

    class SomethingsContr {//Make sure to use plural noun for the class nam
        private $trainersModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            //eg:  $this->trainersModel = new TrainersModel();
        }

        public function someMethod(){
            return true; //On successful operation, return true.
        }
    }

?>