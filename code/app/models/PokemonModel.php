<?php
    include_once 'models/Database.php';
    class PokemonModel extends Database { 

        public function getPokemonColNames() {
            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Pokemon'";
            $resultContainer  = $this->handleQuery($sql); // other args are optional, read handleQuery
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
            $resultContainer ; // declare return val
            /* Construct base_sql string */
            /* https://davidwalsh.name/php-shorthand-if-else-ternary-operators 
               (common for inline conditional concatentation)
            */
            $base_sql = "SELECT pokemon_id, trainer_id, current_level,
                                nickname, breedname
                                FROM ".($active ? "ActivePokemon" : "InactivePokemon"); 
            if (isset($pokemon_id)) { // get unique pokemon
                $sql = $base_sql." WHERE pokemon_id = ?;";
                $bindTypeStr = "i";
                $bindArr = [$pokemon_id];
                $resultContainer  = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }      
            else { // aggregate on all other *specified* column values
                // https://www.w3schools.com/sql/sql_like.asp (sql LIKE)
                $sql = $base_sql."WHERE breedname LIKE '?' && nickname LIKE '?'";

                // Construct types for prepared statement bind type string
                $bindTypeStr = "";
                $bindArr = Array(); 

                // ALWAYS bind breedname, nickname; just conditionally use "%": ALL wildcard.
                $bindTypeStr."ss"; // str breedname, str nickname
                $bindArr[] = isset($breedname) ? $breedname : "%";
                $bindArr[] = isset($nickname) ? $nickname : "%";

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
                    $sql = $base_sql." && current_level = ?;"; 
                    $bindTypeStr."i"; 
                    $bindArr[] = $current_level; 
                }
                elseif (isset($current_level) && isset($upper_current_level)) { // range filtering
                    $sql = $base_sql." && (current_level >= ? && current_level <= ?);"; 
                    $bindTypeStr."ii"; // one i for lowerbound, other for upperbound
                    $bindArr[] = $current_level;
                    $bindArr[] = $upper_current_level;
                }
                else {
                    $sql = $base_sql.";"; // wrap up query and do not add current_level
                }
                $resultContainer  = $this->handleQuery($sql,$bindTypeStr,$bindArr); // if no filtering added ^, 
                                                                          // uses $sql = base_sql
            }       
            return $resultContainer ; 
        }

        public function getAllActivePokemon() {
            /* Prepare args for query handler */
            $sql = "SELECT * FROM ActivePokemon";
            $resultContainer  = $this->handleQuery($sql);
            return $resultContainer ;
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
            // Preparing args for query handler calls
            $arg_list = [$trainer_id, $phone, $email];
            $resultContainer ; // default
            $base_sql = "SELECT * FROM ".($active ? "ActivePokemon" : "InactivePokemon")." ";
            /** Go over all non-null, set args that are provided to see if any succeed.
                * isset(): https://www.php.net/manual/en/function.isset.php
             * These are not in loop because binding varies
             * for each type. 
             * Be wary of using mysqli_num_rows for large queries:
                *  https://www.php.net/manual/de/mysqlinfo.concepts.buffering.php
                *  https://stackoverflow.com/questions/35820810/php-get-result-returns-an-empty-result-set
            */
            
            try { // ensure an argument is provided
                if (!isset($trainer_id) && !isset($phone) && $isset($email)) {
                    throw new Exception("Error: 0 arguments provided");
                }
            }
            catch (Exception $e) {
                return; 
            }

            $resultContainer = null;
            if (isset($trainer_id)) {
                // try
                $sql = $base_sql."WHERE trainer_id = ?";
                $bindTypeStr = "i";
                $bindArr = [$trainer_id];
                $resultContainer  = $this->handleQuery($sql,$bindTypeStr,$bindArr); // return false if failed.
            }
            if (isset($phone) && $resultContainer == null) { // updated from mysqli_num_rows($resultContainer )
                // previous failed, try this
                $sql = $base_sql."INNER JOIN USING (phone) WHERE phone = ?";
                $bindTypeStr = "s";
                $bindArr = [$phone];
                $resultContainer  = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }
            if (isset($email) && $resultContainer == null) { // updated from mysqli_num_rows($resultContainer )
                // previous failed, try this
                $sql = $base_sql."INNER JOIN USING (email) WHERE email = ?";
                $bindTypeStr = "s";
                $bindArr = [$email];
                $resultContainer  = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }

            return $resultContainer ;
        }

        /* assume autoincrement on pokemon? */
        public function addPokemon(int $trainer_id, int $current_level, 
                                   string $nickname, string $breedname) {
            $sql = "INSERT INTO Pokemon(trainer_id,current_level,nickname,breedname) 
                    VALUES (?,?,?,?);";
            $bindTypeStr = "iiss";
            $bindArr = [$trainer_id, $current_level, $nickname, $breedname];
            $resultContainer  = $this->handleQuery($sql,$bindTypeStr,$bindArr);    
            return $resultContainer ;              
        }

        /** should we check if pokemon is active? */
        public function getAllCurrentMoves(int $pokemon_id) {
             // Always contains move description, just slice as needed
            $sql = "SELECT CurrentMoves.move_name, Moves.move_description 
                    FROM CurrentMoves INNER JOIN Moves
                    USING (move_name) WHERE CurrentMoves.pokemon_id = ?;";
            $bindTypeStr = "i";
            $bindArr = [$pokemon_id];
            $resultContainer  = $this->handleQuery($sql,$bindTypeStr,$bindArr);    
            return $resultContainer ;              
        }

        /* At this point, we have performed all necessary 
         checks to ensure safe insertion for replace and 
         addCurrentMove methods. 
        */
        public function replaceCurrentMove(int $pokemon_id, 
                                           string $old_move_name, 
                                           string $new_move_name) {
            $sql = "UPDATE CurrentMoves SET move_name = ? 
                                        WHERE move_name = ? && pokemon_id = ?;";
            $bindTypeStr = "ssi";
            $bindArr = [$new_move_name, $old_move_name, $pokemon_id];
            $resultContainer  = $this->handleQuery($sql,$bindTypeStr,$bindArr);                                                                       
            return $resultContainer ;                                   
        }
        public function addCurrentMove(int $pokemon_id, string $new_move_name) { 
            // define trigger/procedure for insertion (reinforcing degree)
            $sql = "INSERT INTO CurrentMoves(move_name, pokemon_id) 
                    VALUES (?,?);";
            $bindTypeStr = "si";
            $bindArr = [$new_move_name, $pokemon_id];
            $resultContainer  = $this->handleQuery($sql,$bindTypeStr,$bindArr);                                                                       
            return $resultContainer ;
            
        }

    }
?>