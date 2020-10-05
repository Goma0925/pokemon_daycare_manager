<?php 
    class Settings {//Make sure to use plural noun for the class nam

        public static function setup_debug(){
            ini_set('display_errors', 1);
            ini_set("display_startup_errors", 1);
            error_reporting(E_ALL);
        }
    }

?>