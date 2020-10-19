<?php
    include_once 'utils/Query.php'; 
    include 'models/ServiceRecordsModel.php';
    include 'models/BusinessStatesModel.php';
    include 'models/PokemonModel.php';
 
    /* These are not necessarily for individuals,
        but rather, they are aggregates for overall business 
        analytics 
    */
    class AnalyticsModel { 
        private $serviceRecModel;
        private $busStatesModel;
        public function __construct() {
            $this->serviceRecModel = new ServiceRecordsModel();
            $this->busStatesModel = new BusinessStatesModel();
        }

        /* Reports on sales */
        /* Get the total sales that the daycare has made over a given
           period of time.

           period : Array($start_time, $end_time)
        */
        public function getSales($period){ // default to get all if not specified (do later)
            if (isset($period[0]) && isset($period[1])) {
                $start = $period[0];
                $end = $period[1];
                $query = new Query();
                SELECT CAST(date_changed AS DATE), COUNT(service_record_id) 
                FROM ServiceRecords 
                    INNER JOIN BusinessStates USING (bstate_id) 
                    GROUP BY CAST(date_changed AS DATE) ;
                $sql = "...";
                $resultContainer = $query->handleQuery(); 
                return $resultContainer;                  
            }                
        }

        // SELECT start_time,COUNT(service_record_id) 
        // FROM InactiveServiceRecords WHERE (start_time >= ? && end_time <= ?) GROUP BY CAST(start_time AS DATE);

        /* Reports on ratings */
        /* Getting ratings

          IN
            period : Array($start_time, $end_time)
            n_of_stars : number of stars to filter on
          OUT 
            period
            number of stars 
            rating description
        */ 
        public function getRatings($period, $n_of_stars){

        }

        /* Get descriptions of ratings */

        /* Reports on moves */
        /* Get total number of moves learned during period */
        public function movesLearnedByPeriod($period) {

        }

        /* Get total number of eggs laid over period */
        public function eggsLaidByPeriod($period) {

        }        

        /* Number of pokemon enrolled over period */
        public function pokemonEnrolled($period) {

        }

        public function someMethod(){
            $query = new Query(); 
            $sql = "...";
            $resultContainer  = $query->handleQuery(); 
            return $resultContainer;
        }
    }
?>