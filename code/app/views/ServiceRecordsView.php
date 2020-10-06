<?php
    include 'models/ServiceRecordsModel.php';

    class ServiceRecordsView { //Make sure to use plural noun for the class name
        private $serviceRecordsModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            // e.g:   $this->trainersModel = new TrainersModel();
            $this->serviceRecordsModel = new ServiceRecordsModel();
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

        public function init_serviceRecordsTable($action){
            //1. Get data from model
            $resultContainer = $this->serviceRecordsModel->getAllServiceRecords();
            if ($resultContainer->isSuccess()) {
                $this->buildTableForm("","get", ["Select","RecordID","Start Date", "End Date", "PokemonID", "TrainerID"],
                                                        $resultContainer,"service_record_id"
                              );
            }
            else {
                echo "Some error has occurred.";
            }

            //2. Render HTML 
        }
    }
?>