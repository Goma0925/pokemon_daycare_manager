<?php
    include 'models/setModelClassNameHere.php';

    class SomethingsView { //Make sure to use plural noun
        private $someModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            // e.g:   $this->trainersModel = new TrainersModel();
        }

        public function someMethod(){
            //1. Get data from model
            //eg: $this->someModel->doSomething();

            //2. Render HTML 
        }
    }
?>