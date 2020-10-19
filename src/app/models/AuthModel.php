<?php
    include_once 'utils/Query.php'; // NEW
 
    class AuthModel {
        public function login($username, $password){
            // Returns true/false depending on login success.
            $query = new Query();
            $resultContainer = new ResultContainer();
            $resultContainer->setFailure();

            //Get password match with SHA2 hash function
            $sql = "SELECT username, pass
                    FROM Auth
                    WHERE pass = SHA2(?, 224)";
            $bindTypeStr = "s";
            $bindArr = [$password];
            $query->setAll($sql, $bindTypeStr, $bindArr);
            $hashResult = $query->handleQuery();   
            if ($matched_user = $hashResult->get_mysqli_result()->fetch_row()){
                if ($username == $matched_user[0]){
                    // Given password's hashed value matched with the one in DB!
                    $resultContainer->setSuccess();
                }else{
                    $resultContainer->setFailure();
                }
            }
            return $resultContainer; 
        }
    }
?>