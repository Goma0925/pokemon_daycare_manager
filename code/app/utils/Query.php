<?php 
    include_once 'models/Database.php';
    include_once 'ResultContainer.php';

    // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // includes try/catch
    class Query extends Database { // we can use Database's connect method
        
        private $conn; // connection instance

        private $sqlArr = Array(); // arr representation
        private $sql = ""; // string used for actual query

        private $bindTypeArr = Array(); // arr representation
        private $bindTypeStr = ""; // string used for actual query

        private $bindArr = Array(); // arr representation is what bind_param uses
        
        public function __construct(){ // set up connection and throw if fails
            try {
                if(!$this->conn = $this->connect()) {
                    throw new Exception($conn->error);                   
                }
            }
            catch (Exception $e) {
                // just led the exception be logged
                
            }
        } 
        
        /* Note 
           Set methods set the final states (not intermediate states).
           Look at the following example (ServiceRecordsModel.php, endService()):
                $query->setBindArr([$end_date, $service_record_id]);
                $query->setBindTypeStr("si");
           When we are binding the same thing everytime, these set methods are used.
           
           Add methods are used to give more leverage over conditional constructions.
           ServiceRecordsModel.php, getServiceRecords() demonstrates the use of adds. 

           Removers are also used in complex querying, sometimes alongside Add methods.
           PokemonModel getPokemonByTrainer() is a good example of these combined. 
        */

        // Bind Type String representation
        public function addBindType($s_type) { // add a character to bind type arr
            $this->bindTypeArr[] = $s_type; 
        }
        public function setBindTypeStr($s_all_types) { // set the actual string all at once
            $this->bindTypeStr = $s_all_types;
        }
        public function removeLastBindType() {
            $removed = array_pop($this->bindTypeArr);
        }

        // Parameter binding representation
        public function addBindArrElem($param) { // add single element
            $this->bindArr[] = $param; 
        }
        public function setBindArr($bindArr) { // add series of elements
            $this->bindArr = $bindArr; 
        }
        public function removeLastBindArr() {
            $removed = array_pop($this->bindArr);
        }

        // Sql argument prepping 
        public function addToSql($s_to_append) { // add fragment to query for query building
            $this->sqlArr[] = $s_to_append;
        }
        public function setSql($sql) {
            $this->sql = $sql; 
        }
        public function removeLastSql() {
            $removed = array_pop($this->sqlArr);
        }


        // USEFUL METHODS for common repitition
        public function setAll($sql, $bindTypeStr, $bindArr) {
            $this->setSql($sql);
            $this->setBindTypeStr($bindTypeStr);
            $this->setBindArr($bindArr);
        }
        public function removeLastAll() {
            $this->removeLastSql();
            $this->removeLastBindType();
            $this->removeLastBindArr();
        }

        // Merging representation arrays used by $this->handleQuery()
        public function mergeSql() { 
            /* Only merge if sql was not 
               manually set           */
            if ($this->sql == "") {
                $this->sql = implode($this->sqlArr);
            }
        }
        public function mergeBindType() {
            /* Do not merge bindtypearray 
               if bindTypeStr directly set */
            if ($this->bindTypeStr == "") { 
                $this->bindTypeStr = implode($this->bindTypeArr);
            }
        }
        public function mergeAll() { // performs the two ops above
            $this->mergeSql();
            $this->mergeBindType();
        }

        // Constructs prepared statement queries
        /* If a resultContainer is passed in, 
                reuse that and just reset 
           Else 
                create new container and return it
        */
        public function handleQuery(ResultContainer &$resultContainer = null) {

            /* 
                Nnly merges if members not set manually via sets
                We only care to merge if adders used to build arrays
                for more complex querying
            */
            $this->mergeAll(); // set final $sql and $bindTypeStr

            /* 
                If resultContainer passed to handleQuery,
                modify resultContainer by ref; do not return.
            */
            $should_return = false;

            /* Determine if we handleQuery should
               return or not 
            */
            if (!isset($resultContainer)) {
                $should_return = true;
                $resultContainer = new ResultContainer();
            }
            $stmt; // https://www.php.net/manual/en/class.mysqli-stmt.php

            try { 
                // https://www.php.net/manual/en/mysqli.prepare.php
                if(!($stmt = $this->conn->prepare($this->sql))) { 
                    throw new Exception($this->conn->error); 
                };

                if (!($this->bindTypeStr == "") && !empty($this->bindArr)) {
                    // https://wiki.php.net/rfc/argument_unpacking
                    // https://www.php.net/manual/en/mysqli-stmt.bind-param.php

                    if(!$stmt->bind_param($this->bindTypeStr,...$this->bindArr)){
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
                
                // https://www.php.net/manual/en/mysqli-stmt.error.php
                // echo $stmt->error; // the documentation contradicts itself, report

                // https://www.php.net/manual/en/function.error-log.php
                // logs to /var/log/apache2 by default --> error.log
                // use cat /var/log/apache2/error.log to see err (NOTE: DO NOT EDIT THAT FILE)
                error_log($t."START( ".$this->sql."| bindtype: ".var_dump($this->bindTypeStr)."| binarr: ".var_dump($this->bindArr).")DONE.");

                // Something to output to user. 
                $resultContainer->addErrorMessage($user_error); // not being output to user
                $resultContainer->setFailure();
            }
            finally {
                $this->conn->close(); // close db connection
                if ($should_return) {
                    return $resultContainer; 
                } 
                // otherwise, we just modified the container's reference
            }
        }
    }

?>