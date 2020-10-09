<?php
    include_once 'models/BusinessStatesModel.php';

    class BusinessStatesView { //Make sure to use plural noun for the class name
        private $businessStatesModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            // e.g:   $this->trainersModel = new TrainersModel();
            $this->businessStatesModel = new BusinessStatesModel();
        }
        public function renderBusinessStatesTable(){
            $resultContainer = $this->businessStatesModel->getAllBusinessStates();
            $resultContainer2 = $this->businessStatesModel->getCurrentBusinessState();
            $default_date = $resultContainer2->get_mysqli_result()->fetch_object()->date_changed;

            $temp_date = DateTime::createFromFormat('Y-m-d H:i:s', $default_date);
            $temp_date_form = $temp_date->format('Y-m-d H:i:s');
            $date_for_input_elem = str_replace(" ","T",$temp_date_form);

            if ($resultContainer->isSuccess() && $resultContainer2->isSuccess()) {
                echo '
                    <form id="bstateform" action="settings-page.php" method="post">
                    <table id="business_states" class="table">
                        <thead>
                            <th scope="col">ID</th>      
                            <th scope="col">Date In Effect</th>     
                            <th scope="col">Pokemon Price Per Day</th>  
                            <th scope="col">Max Pokemon Per Trainer</th>  
                            <th scope="col">Flat Egg Price</th> 
                    </thead>
                    <tbody>';
                if ($resultContainer->get_mysqli_result()->num_rows != 0) { // construct rest
                    while ($row = $resultContainer->get_mysqli_result()->fetch_assoc()) {
                        echo '<tr>
                                <td>'.$row["bstate_id"].'</td>
                                <td>'.$row["date_changed"].'</td>
                                <td>'.$row["price_per_day"].'</td>
                                <td>'.$row["max_pokemon_per_trainer"].'</td>
                                <td>'.$row["flat_egg_price"].'</td>
                              </tr>';
                    }
                    echo '<tr class="new_row">
                            <button id="insert_new" class="btn btn-secondary" type="button">Insert New Record</button> 
                            <td id="state_id"></td>
                            <td class="new" id="td_date_changed" > <input id="date_changed" type="hidden" value="'.$date_for_input_elem.'" name="states[date_changed]"></td>

                          </tr>';
                    echo '</tbody></table></form>';
                }
                else { // say no records found
                    echo "NO RECORDS FOUND"; // ACTUALLY DISPLAY THIS CORRECTLY
                }
            }
            else {
                echo ""; // ERROR IS WITH DATABASE
            }
        }

        public function renderInsertRecordButton() {
            
        }
    }
?>