<?php 
    class Database {
        private $conn;
        protected function connect(){
            $dbhost = "localhost";
            $dbuser = "Amon";
            $dbpass = "password";
            $dbname = "daycare";
            $this->conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
            return $this->conn;
        }

        protected function close(){
            $this->conn->close();
        }
    }

?>