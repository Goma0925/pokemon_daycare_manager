<?php

    include_once 'utils/Query.php';
    class PokemonModel { 

        public function getPokemonColNames() {
            $query = new Query();
            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Pokemon';";
            $query->setToSql($sql);
            $resultContainer  = $query->handleQuery(); // other args are optional, read handleQuery
            return $resultContainer ;
        } 

        /** Get 1 or more pokemon 
         * If pokemon_id is specified, returns 1 pokemon record.
         * If pokemon_id is unspecified, all other fields will be used
            * to aggregate results based on the arguments provided (below):
                * $current_level: exact level filter || 
                    * use as lower bound for $upper_current_level 
                    * [$current_level, $upper_current_level].
                * $upper_current_level: 
                    * use as upper bound for $current_level
                    * [$current_level, $upper_current_level].
                * $nickname
                * $breedname
         * If active is set to false, then the aggregation will be done
         * on all pokemon (including those that are active). 
         * Search on range level ** to add
        */
        public function getPokemonByAttr(int $pokemon_id = null,
                                        int $current_level = null,
                                        int $upper_current_level = null,
                                        string $nickname = null,
                                        string $breedname = null,
                                        bool $active = true) {

            $query = new Query();
            // Declare vars
            // $resultContainer = null; 
            // $bindTypeStr = "";
            // $bindArr = Array(); 
            
            /* Construct base_sql string */
            /* https://davidwalsh.name/php-shorthand-if-else-ternary-operators 
               (common for inline conditional concatentation)
            */
            $base_sql = "SELECT pokemon_id, trainer_id, current_level,
                                nickname, breedname
                                FROM ".($active ? "ActivePokemon" : "InactivePokemon"); 
            $query.addToSql($base_sql);

            if (isset($pokemon_id)) { // get unique pokemon
                $query->addToSql(" WHERE pokemon_id = ?;");
                $query->addBindType("i");
                $query->addBindArrElem($pokemon_id);
                $resultContainer  = $query->handleQuery();
            }      
            else { // aggregate on all other *specified* column values
                // https://www.w3schools.com/sql/sql_like.asp (sql LIKE)
                $query->addToSql("WHERE breedname LIKE '?' && nickname LIKE '?'");
                // ALWAYS bind breedname, nickname; just conditionally use "%": ALL wildcard.

                $query->setBindTypeStr("ss"); // str breedname, str nickname
                $bindArr[] = isset($breedname) ? 
                    $query->addBindArrElem($breedname) 
                    : 
                    $query->addBindArrElem("%");
                $bindArr[] = isset($nickname) ? 
                    $query->addBindArrElem($nickname) 
                    : 
                    $query->addBindArrElem("%");

                /* Note (https://www.php.net/manual/en/function.array-push.php): 
                   $arr = Array();
                   $arr[] = 'cool'; 
                   $arr[] = 'awesome'; 
                   var_dump($arr) // output: [0]=> string(4) "cool"
                                             [1]=> string(7) "awesome"
                   // **faster than array_push() for single pushing**
                */
                // If $current_level specified, bind. Otherwise, do not. 
                if (isset($current_level) && !isset($upper_current_level)) { // exact filtering
                    $query->addToSql(" && current_level = ?;");
                    $query->addBindType("i");
                    $query->addBindArrElem($current_level);
                }
                elseif (isset($current_level) && isset($upper_current_level)) { // range filtering
                    $query->addToSql(" && (current_level >= ? && current_level <= ?);");
                    $query->addBindType("ii");   
                    $query->addBindArrElem($current_level);     
                    $query->addBindArrElem($upper_current_level);             
                }
                else {
                    $query->addToSql(";");
                }
                $resultContainer  = $query->handleQuery(); 
            }       
            return $resultContainer; 
        }

        public function getAllActivePokemon() {
            // Declare vars
            $query = new Query();
            $sql = "SELECT * FROM ActivePokemon;";
            $query->setSql($sql);
            $resultContainer  = $query->handleQuery();
            return $resultContainer;
        }

        /* Get pokemon by unique trainer information.
           All arguments provided may be tried. The first
           criteria to return a result with more than 0 rows will be
           the output. If all criteria fail, ... */
        public function getPokemonByTrainer(int $trainer_id = null, 
                                            string $phone = null, 
                                            string $email = null,
                                            $active = true) {                      
            // https://www.php.net/manual/en/functions.arguments.php - default args
            /**  Account for following errors:  
                * Bad phone number form
                * Bad email form                                 
                * Trainer dne (actually might wait on doing this; 
                * requires some intermediate queries. Right now
                * we just determine if any active pokemon with that
                * trainer info exist, which is sufficient.) 
            */

            $query = new Query();
            // Declare vars
            // $resultContainer = null; 
            // $bindTypeStr = "";
            // $bindArr = Array(); 

            $base_sql = "SELECT * FROM ".($active ? "ActivePokemon" : "InactivePokemon")." ";
            $query->addToSql($base_sql);
            /** Go over all non-null, set args that are provided to see if any succeed.
                * isset(): https://www.php.net/manual/en/function.isset.php
             * These are not in loop because binding varies
             * for each type. 
             * Be wary of using mysqli_num_rows for large queries:
                *  https://www.php.net/manual/de/mysqlinfo.concepts.buffering.php
                *  https://stackoverflow.com/questions/35820810/php-get-result-returns-an-empty-result-set
            */
            
            try { // ensure an argument is provided
                if (!isset($trainer_id) && !isset($phone) && !isset($email)) {
                    throw new Exception("Error: invalid arguments provided");
                }
            }
            catch (Exception $e) {
                return; 
            }

            
            // this poses a weird position for resultContainer 
            // temp fix below (should be fixed now):
            // $err_mssgs = Array();
            if (isset($trainer_id)) {
                // try
                $query->addToSql("WHERE trainer_id = ?");
                $query->addBindType("i");
                $query->addBindArrElem($trainer_id);
                $resultContainer = $query->handleQuery();
            }
            if (isset($phone) && ($resultContainer->get_mysqli_result()->num_rows == 0)) { 
                // previous failed, try this
                // $err_mssgs[] = "TrainerID not found";
                $resultContainer->addErrorMessage("Phone not found");
                $query->removeLastAll(); // clears last sql, bindtype, bindtypestr
                $query->addToSql("INNER JOIN USING (phone) WHERE phone = ?");
                $query->addBindType("s");
                $query->addBindArrElem($phone);
                $query->handleQuery($resultContainer); // passed as ref
            }
            if (isset($email) && ($resultContainer->get_mysqli_result()->num_rows == 0)) { 
                // previous failed, try this
                // $err_mssgs[] = "Phone not found";
                $resultContainer->addErrorMessage("Phone not found");
                $query->removeLastAll();
                $query->addToSql("INNER JOIN USING (email) WHERE email = ?");
                $query->addBindType("s");
                $query->addBindArrElem($email);
                $query->handleQuery($resultContainer); // passed as ref
            }
            if ($resultContainer->get_mysqli_result()->num_rows == 0) {
                // $err_mssgs[] = "Email not found";
                $resultContainer->addErrorMessage("Email not found");

            }
            // final container to return; 
            // $resultContainer->mergeArrayErrorMessages($err_mssgs); 
            return $resultContainer;
        }

        public function addPokemon(int $trainer_id, int $current_level, 
                                   string $nickname, string $breedname) {
            $query = new Query();
            $sql = "INSERT INTO Pokemon(trainer_id,current_level,nickname,breedname) 
                    VALUES (?,?,?,?);";
            $bindTypeStr = "iiss";
            $bindArr = [$trainer_id, $current_level, $nickname, $breedname];
            $query->setAll($sql, $bindTypeStr, $bindArr);
            $resultContainer  = $query->handleQuery();    
            return $resultContainer;              
        }

        public function getAllCurrentMoves(int $pokemon_id) {
             // Always contains move description, just slice as needed
            $query = new Query();
            $sql = "SELECT CurrentMoves.move_name, Moves.move_description 
                    FROM CurrentMoves INNER JOIN Moves
                    USING (move_name) WHERE CurrentMoves.pokemon_id = ?;";
            $bindTypeStr = "i";
            $bindArr = [$pokemon_id];
            $query->setAll($sql, $bindTypeStr, $bindArr);
            $resultContainer = $query->handleQuery();    
            return $resultContainer;              
        }

        /* At this point, we have performed all necessary 
         checks to ensure safe insertion for replace and 
         addCurrentMove methods. 
        */
        public function replaceCurrentMove(int $pokemon_id, 
                                           string $old_move_name, 
                                           string $new_move_name) {
            $query = new Query();
            $sql = "UPDATE CurrentMoves SET move_name = ? 
                                        WHERE move_name = ? && pokemon_id = ?;";
            $bindTypeStr = "ssi";
            $bindArr = [$new_move_name, $old_move_name, $pokemon_id];
            $query->setAll($sql, $bindTypeStr, $bindArr);
            $resultContainer  = $query->handleQuery();                                                                       
            return $resultContainer ;                                   
        }

        public function addCurrentMove(int $pokemon_id, string $new_move_name) { 
            // define trigger/procedure for insertion (reinforcing degree)
            $query = new Query();
            $sql = "INSERT INTO CurrentMoves(move_name, pokemon_id) 
                    VALUES (?,?);";
            $bindTypeStr = "si";
            $bindArr = [$new_move_name, $pokemon_id];
            $query->setAll($sql, $bindTypeStr, $bindArr);
            $resultContainer = $query>handleQuery();                                                                       
            return $resultContainer;
            
        }

    }
?>