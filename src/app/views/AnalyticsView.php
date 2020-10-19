<?php
    include 'models/ServiceRecordsModel.php';
    include 'models/BusinessStatesModel.php';

    class AnalyticsView { //Make sure to use plural noun for the class name
        private $serviceRecModel;
        private $busStatesModel;
        public function __construct() {
            $this->serviceRecModel = new ServiceRecordsModel();
            $this->busStatesModel = new BusinessStatesModel();
        }

        // Reports that we may we want

        /* Get the total sales that the daycare has made over a given
           period of time.
           period : Array($start_time, $end_time)
        */
        public function getSalesByPeriod($period){

        }

        /* Getting ratings
          period : Array($start_time, $end_time)
          n_of_stars :
        */ 
         public function getRatings($period, $n_of_stars){

        }


    }
?>