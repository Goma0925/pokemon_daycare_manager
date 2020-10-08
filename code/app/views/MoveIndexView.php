<?php
    include_once 'models/MoveIndexModel.php';

    class MoveIndexView { //Make sure to use plural noun for the class name
        private $moveIndexModel;
        private $move_num;
        private $all_rows;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            $this->moveIndexModel = new MoveIndexModel();
            $resultContainer = $this->moveIndexModel->getMoveIndex();
            $this->move_num = $resultContainer->get_mysqli_result()->num_rows;
            $this->all_rows = $resultContainer->get_mysqli_result()->fetch_all();
        }

        public function moveDropdownBox($input_name, $move_number){
            //Renders all available moves in a drop down box.
            $success = false;
            echo '
                <div class="form-group">
                <select class="form-control" name="'.$input_name.'-'.$move_number.'">
                    <option value="">None</option>';
            //Render all possible moves from Moves table.
            if ($this->all_rows != null){
                for ($i=0; $i<$this->move_num; $i++){
                    $success = true;
                    echo '
                        <option value="'.$this->all_rows[$i][0].'">'.$this->all_rows[$i][0].'</option>
                        ';
                }
            }else{
                $success = false;
                echo "Failure";
            }
            
            echo '
                </select>
            </div>
            ';
        }
    }
?>