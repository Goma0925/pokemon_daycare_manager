<?php
    include 'models/TrainersModel.php';

    class TrainersView {
        private $trainersModel;
        public function __construct() {
            //Make sure you don' put the $ sign in front of the variable name when using $this keyword!
            $this->trainersModel = new TrainersModel();
        }

        public function trainersTableByName($name){
            $result = $this->trainersModel->getTrainersByName($name);
            echo '
            <form method="post">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Select</th>   
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                ';
            while ($row = $result->fetch_assoc()) {
                echo '  <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="trainer-table-by-name" value="'.$row["trainer_id"].'">
                                </div>
                            </td>
                            <td>'.$row["trainer_name"].'</td>
                            <td>'.$row["phone"].'</td>
                            <td>'.$row["email"].'</td>
                        </tr>
                ';
            }
            echo '
                    </tbody>
                </table>';
            if ($result->num_rows!=0){
                echo '
                    <input type="submit">
                ';
            }
            echo '
            </form>
            ';

            //Render "not found" message if no records were found.
            if ($result->num_rows==0){
                echo '
                        <p width="100%" style="text-align: center;">No matching record found for "'.$name.'".</p>
                ';
            }
        }
    }
?>