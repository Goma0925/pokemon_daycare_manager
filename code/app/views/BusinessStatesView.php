<?php
    include 'models/BusinessStatesModel.php';

    class BusinessStatesView { 
        private $businessStatesModel;
        public function __construct() {
            $this->businessStatesModel = new BusinessStatesModel();
        }
        public function renderBusinessStatesTable(){
            $resultContainer = $this->businessStatesModel->getBusinessStates(false, false); 
            if ($resultContainer->isSuccess()) {
                echo '
                    <form id="bstateform" action="settings-page.php" method="post">
                    <table id="business_states" class="table">
                        <thead>
                             
                            <th scope="col">Date In Effect</th>     
                            <th scope="col">Pokemon Price Per Day</th>  
                            <th scope="col">Max Pokemon Per Trainer</th>  
                            <th scope="col">Flat Egg Price</th> 
                    </thead>
                    <tbody>';
                $empty = $this->businessStatesModel->getCurrentBusinessState()->get_mysqli_result()->num_rows == 0 ? true : false; 
                $next_most_recent_date;
                if (!$empty) { 
                    $most_recent_date = $this->businessStatesModel->getCurrentBusinessState()->get_mysqli_result()->
                        fetch_object()->date_changed;
                    $next_most_recent_date = date('Y-m-d', strtotime($most_recent_date .' +1 day')); 
                }
                else {
                    $next_most_recent_date = date('Y-m-d');
                }
                while ($row = $resultContainer->get_mysqli_result()->fetch_assoc()) {
                    echo '<tr>
                            <td>'.$row["date_changed"].'</td>
                            <td>'.$row["price_per_day"].'</td>
                            <td>'.$row["max_pokemon_per_trainer"].'</td>
                            <td>'.$row["flat_egg_price"].'</td>
                            </tr>';
                }
                echo '<tr class="new_row">
                        <button id="insert_new" class="btn btn-secondary" type="button">Insert New Record</button> 
                        <td class="new" id="td_date_changed" > <input id="date_changed" type="hidden" value="'.$next_most_recent_date.'" name="states[date_changed]"></td>

                        </tr>';
                echo '</tbody></table></form>';
            }
            else {
                return $resultContainer; // ERROR IS WITH DATABASE
            }
        }
    }
?>