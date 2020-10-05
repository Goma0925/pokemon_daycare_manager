<?php 
    include_once 'utils/ResultContainer.php';
    // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // includes try/catch
    class Database {
        private $conn;
        private function connect(){
            // Deprecated: Use of handleQuery() is recommended. 
            $dbhost = "localhost";
            $dbuser = "ming";
            $dbpass = "password";
            $dbname = "daycare";
            $this->conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
            return $this->conn;
        }

        // Constructs prepared statement and provides robust err handling
        // Always return the custom ResultContainer object
        // https://www.php.net/manual/en/functions.arguments.php - default args
        public function handleQuery($sql, $bindTypeStr=null, $bindArr=null) {
            
            // Declare vars 
            $resultContainer = new ResultContainer();
            $stmt; // https://www.php.net/manual/en/class.mysqli-stmt.php
            $conn;

            // The reason why we keep try/catch block is 
            // 1) catch intermediate errors and throw when necessary,
            // 2) **halt code execution**
            try { 
                if(!$conn = $this->connect()) {
                    throw new Exception($conn->error);                   
                };

                // https://www.php.net/manual/en/mysqli.prepare.php
                if(!($stmt = $conn->prepare($sql))) { 
                    throw new Exception($conn->error); 
                };
                if (!is_null($bindTypeStr) && !is_null($bindArr)) {
                    // https://wiki.php.net/rfc/argument_unpacking
                    // https://www.php.net/manual/en/mysqli-stmt.bind-param.php
                    if(!$stmt->bind_param($bindTypeStr,...$bindArr)){
                        $stmt->close();
                        throw new Exception($stmt->error);
                    }; 
                }
                if (!$stmt->execute()) {
                    $stmt->close();
                    throw new Exception($stmt->error);
                }; 

                // We are good if we made it this far. 
                // consult documentation: https://www.php.net/manual/en/mysqli-stmt.get-result.php
                $result = $stmt->get_result();
                $resultContainer->set_mysqli_result($result);
            } 
            catch (Throwable $t) {
                // Error to send to user
                $user_error = "Database error has occurred.
                Sorry for the inconvenience. Report to organization's 
                tech support.";
                echo $user_error;
                
                // https://www.php.net/manual/en/mysqli-stmt.error.php
                // echo $stmt->error; // the documentation contradicts itself, report

                // https://www.php.net/manual/en/function.error-log.php
                // logs to /var/log/apache2 by default --> error.log
                // use cat /var/log/apache2/error.log (NOTE: DO NOT EDIT THAT FILE)
                error_log("Error (".$t.") occurred at ".date('Format String')."\n");

                // Something to output to user. 
                $resultContainer->addErrorMessage($user_error);
                $resultContainer->setFailure();
            }
            finally {
                $conn->close(); // close db connection
                // $stmt->close(); // can only close statement if it was prepped?
                return $resultContainer; 
            }
        }
    }

?>