<?php 
    class Settings {

        public static function setup_debug(){
            ini_set('display_errors', 1);
            ini_set("display_startup_errors", 1);
            error_reporting(E_ALL);
        }
    }

?>