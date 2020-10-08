
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
function updateValue(element,col,id) {
    var selector = ("#").concat(col.concat(id));
    var new_val = element.innerText.concat("|",id).concat("|",col);
    console.log($(selector).attr("value"));
    $(selector).attr("value",new_val);
    console.log($(selector).attr("value"));
}

// $("#services").submit(function (event) {
//     alert( "Handler for .submit() called." );
//     // event.preventDefault();
//     console.log("submitting");
// }); 
</script>


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
            $field_info = $table_data->get_mysqli_result()->fetch_fields(); 
            echo '
                <form id="services" action="'.$action.'" method="'.$method.'">
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
                            <tr>';                
                foreach ($field_info as $f) {
                    if ($f->name == "start_time" || $f->name == "end_time") {
                        $id = $row[$input_value];
                        $unique = $f->name.$row[$input_value];                        
                        echo '
                            <td>
                                <input type="hidden" id="'.$unique.'" name="res[]" value="default">
                                <div contenteditable="true" 
                                    edit_type="click"
                                    onBlur=updateValue(this,"'.$f->name.'","'.$id.'")
                                    > '.$row[$f->name].
                                '</div>
                            </td>
                        ';

                    } 
                    else {
                        echo '
                            <td>'.$row[$f->name].'</td>
                        ';
                    }


                }
                echo '
                            <td>
                                <div>
                                    <input 
                                    id="'.$row[$input_value].'"
                                        type="submit"  
                                        name="'.$row[$input_value].'"
                                        > 
                                </div>
                            </td>';
                echo '   </tr>';

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
                $this->buildTableForm("service-search.php","post", 
                    ["RecordID","Start Date", "End Date", "PokemonID", "TrainerID","Save/Update"],
                    $resultContainer,"service_record_id");
            }
            else { // do not render at all (maybe render some error, just depends)
                var_dump($resultContainer->getErrorMessages());
            } 
        }


 
    }
?>