<?php 
    include_once 'utils/Credentials.php';
    // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // includes try/catch
    class Database {
        private $conn;
        protected function connect(){
            $dbhost = Credentials::$host;
            $dbuser = Credentials::$user;
            $dbpass = Credentials::$password;
            $dbname = Credentials::$dbname;
            $this->conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
            return $this->conn;
        }
    }
?>