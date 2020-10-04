<?php
    include 'models/Database.php';
    class PokemonModel extends Database { 

        public function getPokemonColNames() {
            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Pokemon'";
            $result = $this->handleQuery($sql); // other args are optional, read handleQuery
            return $result;
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
            $result; // declare return val
            /* Construct base_sql string */
            /* https://davidwalsh.name/php-shorthand-if-else-ternary-operators 
               (common for inline conditional concatentation)
            */
            $base_sql = "SELECT pokemon_id, trainer_id, current_level,
                                nickname, breedname
                                FROM ".($active ? "ActivePokemon" : "Pokemon"); 
            if (isset($pokemon_id)) { // get unique pokemon
                $sql = $base_sql." WHERE pokemon_id = ?;";
                $bindTypeStr = "i";
                $bindArr = [$pokemon_id];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }      
            else { // aggregate on other column values
                // https://www.w3schools.com/sql/sql_like.asp (sql LIKE)
                $sql = $base_sql."WHERE breedname LIKE '?' && nickname LIKE '?'";

                // Construct types for prepared statement bind type string
                $bindTypeStr = "";
                $bindArr = Array(); 

                // always bind breedname and nickname, just conditionally use "%": ALL wildcard
                $bindTypeStr."ss"; // str breedname, str nickname
                $bindArr[] = isset($breedname) ? $breedname : "%";
                $bindArr[] = isset($nickname) ? $nickname : "%";

                // do not always bind current_level, add to typestr and arr accordingly
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
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr); // if no filtering added ^, 
                                                                         // uses $sql = base_sql
            }       
            return $result; 
        }

        public function getAllActivePokemon() {
            /* Prepare args for query handler */
            $sql = "SELECT pokemon_id, trainer_id, current_level,
                    nickname, breedname FROM ActivePokemon";
            $result = $this->handleQuery($sql);
            return $result;
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
            $result = []; // default
            $base_sql = "SELECT pokemon_id, trainer_id, current_level,
            nickname, breedname FROM ".($active ? "ActivePokemon" : "Pokemon");

            /** Go over all non-null, set args that are provided to see if any succeed.
                * isset(): https://www.php.net/manual/en/function.isset.php
             * These are not in loop because binding varies
             * for each type. 
             * Be wary of using mysqli_num_rows for large queries:
                *  https://www.php.net/manual/de/mysqlinfo.concepts.buffering.php
                *  https://stackoverflow.com/questions/35820810/php-get-result-returns-an-empty-result-set
            */
            if (isset($trainer_id)) {
                // try
                $sql = $base_sql."WHERE trainer_id = ?";
                $bindTypeStr = "i";
                $bindArr = [$trainer_id];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr); // return false if failed.
            }
            if (isset($phone) && mysqli_num_rows($result) == 0) {
                // previous failed, try this
                $sql = $base_sql."INNER JOIN USING (phone) WHERE phone = ?";
                $bindTypeStr = "s";
                $bindArr = [$phone];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }
            if (isset($email) && mysqli_num_rows($result) == 0) {
                // previous failed, try this
                $sql = $base_sql."INNER JOIN USING (email) WHERE email = ?";
                $bindTypeStr = "s";
                $bindArr = [$email];
                $result = $this->handleQuery($sql,$bindTypeStr,$bindArr);
            }
            return $result; // if result set empty at this point, bad search args.
        }

   
    }
?>