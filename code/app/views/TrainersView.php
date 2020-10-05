<?php
    include 'models/TrainersModel.php';

    class TrainersView {
        private $trainersModel;
        public function __construct() {
            $this->trainersModel = new TrainersModel();
        }

        public function trainerTableByName($name){
            $resultContainer = $this->trainersModel->getTrainersByName($name);
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
            while (!is_null($resultContainer->get_mysqli_result()) &&
                   $row = $resultContainer->get_mysqli_result()->fetch_assoc()) {
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
            if (!is_null($resultContainer->get_mysqli_result()) && $resultContainer->get_mysqli_result()->num_rows!=0){
                echo '
                    <input type="submit" value="Select trainer">
                ';
            }
            echo '
            </form>
            ';

            //Render "not found" message if no records were found.
            if (!is_null($resultContainer->get_mysqli_result()) && $resultContainer->get_mysqli_result()->num_rows==0){
                echo '
                        <p width="100%" style="text-align: center;">No matching record found for "'.$name.'".</p>
                ';
            }
        }

        public function trainerRegistrationForm(){
            echo '
            <form action="" method="post">
                <div class="form-group">
                    <label for="name-form">Trainer name</label>
                    <input type="text" class="form-control" id="name-form" name="name" placeholder="Enter name" required>
                </div>
                <div class="row">
                    <div class="col">
                        <p>Email: <input type="email" class="form-control" name="email" placeholder="Email" required></p>
                    </div>
                    <div class="col">
                        <p>Phone: <input type="text" class="form-control" name="phone" placeholder="XXX-XXX-XXXX" required></p>
                    </div>
                </div>
                <input type="submit">
            </form>
            ';
        }

        public function registrationSuccessMessage(){
            echo "<p>Successfully registered a new trainer!</p>";
        }
    }
?>