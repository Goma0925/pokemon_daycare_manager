<?php 
    include_once 'utils/ResultContainer.php';
    class Database {
        private $conn;
        private function connect(){
            // Deprecated: Use of handleQuery() is recommended. 
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

        // Constructs prepared statement and provides robust err handling
        // Always return the custom ResultContainer object
        // https://www.php.net/manual/en/functions.arguments.php - default args
        public function handleQuery($sql, $bindTypeStr=null, $bindArr=null) {
            $resultContainer = new ResultContainer();
            $stmt;
            /** *If anything fails, throw exception.
                *If stmt causes error, we can use its attr 
            **/
            try { 
                $stmt = $this->connect()->prepare($sql);
                if (!is_null($bindTypeStr) && !is_null($bindArr)) {
                    // https://wiki.php.net/rfc/argument_unpacking
                    $stmt->bind_param($bindTypeStr,...$bindArr); 
                }
                $stmt->execute(); 
                $result = $stmt->get_result(); // consult documentation: https://www.php.net/manual/en/mysqli-stmt.get-result.php
                $resultContainer->set_mysqli_result($result);
            } 
            catch (Exception $e) {
                /* we have technical errors and user defined errors.
                   how should we handle technical errors? we probably
                   should return a general message to user because they
                   will not be debugging anything. these technical errors 
                   will really just convey bugs in code because we might be 
                   binding something we should never bind and such. 
                   thus, we can just echo out a general message to user when
                   facing technical errors. 
                */

                // https://www.php.net/manual/en/mysqli-stmt.error.php
                $result = $stmt->error; 

                // https://www.php.net/manual/en/function.error-log.php
                error_log("Error (".$result.") occurred at ".date('Format String')."\n",
                "~/class/csc362_project/code/app/err_logs/errors.log");
                // ^ overkill to have reporting hit an email?

                // Something to output to user. 
                $user_error = "Database communication error. Sorry for the inconvenience. Report to organization's 
                tech support.";

                /** error handling below commented out (perfect for user defined)
                    * let's expand this to define our own handlers and exceptions
                    * so that we can learn to build error reporting like you have 
                    * started 
                **/
                $resultContainer->addErrorMessage($user_error);
                $resultContainer->setFailure();
            }
            finally {
                $this->close();
                return $resultContainer; 
            }
        }
    }

?>