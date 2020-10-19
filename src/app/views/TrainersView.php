<?php
    include_once 'models/TrainersModel.php';

    class TrainersView {
        private $trainersModel;
        public function __construct() {
            $this->trainersModel = new TrainersModel();
        }

        public function checkInOutSelectionBox(){
            echo '
            
            ';
        }

        public function trainerSelectionTableByName(string $name, string $button_name, string $action, 
                                                    string $method, Array $form_params){
            //        $name: Trainer's name
            //      $action: URI to jump after hitting select user button. The action value to put in HTML form.
            //      $method: Method type to send the form with. GET, POST, etc.
            // $form_params: An array of (name, value) pairs of form parameters to send with the HTML form.
            $resultContainer = $this->trainersModel->getTrainersByName($name);
            if ($resultContainer->isSuccess()) {
                echo '
                <form action="/submit" method="'.$method.'">';

                //Render hidden input based on $form_params
                foreach ($form_params as $key=>$value){
                    echo '
                    <input type="hidden" name="'.$key.'" value="'.$value.'">
                    ';
                };
                
                echo '
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
                while ($row = $resultContainer->get_mysqli_result()->fetch_assoc()) {
                    echo '  <tr>
                                <td>
                                    <div class="form-check">                                     
                                       <input class="form-check-input" type="radio" name="trainer" value="'.$row["trainer_id"].'" required>
                                    </div>
                                </td>
                                <td>'.$row["trainer_name"].'</td>
                                <td>'.$row["phone"].'</td>
                                <td>'.$row["email"].'</td>
                            </tr>
                    ';
                }
                //Render the check-in/out buttons if there are search results.
                if ($resultContainer->get_mysqli_result()->num_rows!=0){
                    echo '  
                            <tr>
                                <td colspan="4"><button type="submit" value="'.$action.'" formaction="'.$action.'" style="float: right;margin-right:20px;" class="btn btn-info">'.$button_name.'</button></td>
                            </tr>
                    ';
                }
                //Render "not found" message if no records were found.
                if ($resultContainer->get_mysqli_result()->num_rows==0){
                    echo '
                            <tr>
                                <td colspan="12" width="100%" style="text-align: center;">No trainers found for "'.$name.'".</td>
                            </tr>
                    ';
                }
                echo '
                        </tbody>
                    </table>
                </form>';
            }
            else {
                foreach ($resultContainer->getErrorMessages() as $errorMessage){
                    echo "<p>".$errorMessage."</p>";
                }
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

        public function registrationSuccessBox(int $trainer_id){
            echo '
            <div class="jumbotron">
                <h1 class="display-4">Trainer registration complete!</h1>
                <p class="lead">The trainer is registered successfully.</p>
                <hr class="my-4">
                <p class="lead" style="float:right;">
                    <a class="btn btn-info" href="register-pokemon.php?redirect-to=check-in-confirmation&trainer='.$trainer_id.'" role="button">Register pokemon</a>
                </p>
            </div>';
        }
    }
?>