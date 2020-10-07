<?php
    include 'models/ServiceRecordsModel.php';
    include 'models/TrainersModel.php';

    class ServiceRecordsView { //Make sure to use plural noun for the class name
        private $serviceRecordsModel;
        private $trainersModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            // e.g:   $this->trainersModel = new TrainersModel();
            $this->serviceRecordsModel = new ServiceRecordsModel();
            $this->trainersModel = new TrainersModel();
        }

        public function buildTableForm($action, $method, $row_headers, $table_data, $input_value) {
            // make sure $input value matches a real column name
            // makesure rowheaders equals number of rows in table 
            // restrict method to post and get
            $field_info = $table_data->get_mysqli_result()->fetch_fields(); 
            // var_dump($field_info)
            echo '
                <form action="'.$action.'" method="'.$method.'">
                    <table class="table">
                        <thead>'; 
                        echo '<tr>';
            foreach ($row_headers as $rhead) {
                echo '  
                                <th scope="col">'.$rhead.'</th>        
                ';
            }
            echo '          </tr>';
            echo "     </thead>";
            echo "     <tbody>";
            while ($row = $table_data->get_mysqli_result()->fetch_assoc()) {
                echo '
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                        type="radio" name="'.strtolower($field_info[0]->table).'" 
                                        value="'.$row[$input_value].'"> 
                                    </div>
                                </td>
                            ';
                foreach ($field_info as $f) {
                    echo '
                                <td>'.$row[$f->name].'</td>
                        ';
                }
                echo '          </tr>';

            }    
            echo '  </tbody>
                </table>
            </form>'; 
        }

        public function buildServiceRecordsTable($action, string $by = null, $value = null, $status = null){
            //1. Get data from model
            $resultContainer; 
            $status = intval($status);
            if (!empty($value)) {
                $criteria = $by; 
                if (strpos($criteria, 'id') !== false) { $value = intval($value); }
                switch ($criteria) {
                    case "trainer_id":
                        $resultContainer = $this->serviceRecordsModel->getServiceRecordsByTrainerID($value, $status);
                        break;
                    case "service_record_id":
                        $resultContainer = $this->serviceRecordsModel->getServiceRecordByID($value, $status);
                        break;
                    case "pokemon_id":
                        $resultContainer = $this->serviceRecordsModel->getServiceRecordsByPokemonID($value, $status);
                        break;
                  }
            }
            else {
                switch ($status) {
                    case 0:
                        $resultContainer = $this->serviceRecordsModel->getAllInactiveServiceRecords();
                    break;
                    case 1:
                        $resultContainer = $this->serviceRecordsModel->getAllActiveServiceRecords();

                    break;
                    case 2:
                        $resultContainer = $this->serviceRecordsModel->getAllServiceRecords();
                    break; 
                }
            }

            if ($resultContainer->isSuccess()) { // will render based on what was set above
                // var_dump($resultContainer->get_mysqli_result());
                $this->buildTableForm($action,"get", 
                    ["Edit","RecordID","Start Date", "End Date", "PokemonID", "TrainerID"],
                    $resultContainer,"service_record_id");
            }
            else { // do not render at all (maybe render some error, just depends)
                var_dump($resultContainer->getErrorMessages());
            } 
        }


 
    }
?>