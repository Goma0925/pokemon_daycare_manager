<?php
    include 'models/NotificationModel.php';

    class NotificationView {
        private $notificationModel;
        public function __construct() {
            $this->notificationModel = new NotificationModel();
        }

        public function CreateAllTable(){
            // $name: Trainer's name
            // $action: URI to jump after hitting select user button. The action value to put in HTML form.
            $resultContainer = $this->notificationModel->getAllNotifcations();
            if ($resultContainer->isSuccess()) {
                echo '
                <form action="search-notification.php" method="GET">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Select</th>   
                                <th scope="col">Trainer</th>
                                <th scope="col">date created</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                    ';
                while ($row = $resultContainer->get_mysqli_result()->fetch_assoc()) {
                    echo '  <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="trainer" value="'.$row["notification_id"].'">
                                    </div>
                                </td>
                                <td>'.$row["trainer_name"].'</td>
                                <td>'.$row["date_created"].'</td>
                            </tr>
                    ';
                }
                echo '
                        </tbody>
                    </table>';
                if ($resultContainer->get_mysqli_result()->num_rows!=0){
                    echo '
                        <input type="submit" value="Select trainer">
                    ';
                }
                echo '
                </form>
                ';

                //Render "not found" message if no records were found.
                if ($resultContainer->get_mysqli_result()->num_rows==0){
                    echo '
                            <p width="100%" style="text-align: center;">Could not fetch data".</p>
                    ';
                }
            }
            else {
                foreach ($resultContainer->getErrorMessages() as $errorMessage){
                    echo "<p>".$errorMessage."</p>";
                }
            }
        }



        public function CreateEggTable(){
            // $name: Trainer's name
            // $action: URI to jump after hitting select user button. The action value to put in HTML form.
            $resultContainer = $this->notificationModel->getEggNotifcations();
            if ($resultContainer->isSuccess()) {
                echo '
                <form action="search-notification.php" method="GET">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Select</th>   
                                <th scope="col">Date Created</th>
                                <th scope="col">Trainer</th>
                                <th scope="col">Parent 1</th>
                                <th scope="col">Parent 2</th>
                               
                               
                            </tr>
                        </thead>
                        <tbody>
                    ';
                while ($row = $resultContainer->get_mysqli_result()->fetch_assoc()) {
                    echo '  <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="trainer" value="'.$row["notification_id"].'">
                                    </div>
                                </td>
                                <td>'.$row["date_created"].'</td>
                                <td>'.$row["trainer_name"].'</td>
                                <td>'.$row["parent1"].'</td>
                                <td>'.$row["parent2"].'</td>
                                
                            </tr>
                    ';
                }
                echo '
                        </tbody>
                    </table>';
                if ($resultContainer->get_mysqli_result()->num_rows!=0){
                    echo '
                        <input type="submit" value="Select trainer">
                    ';
                }
                echo '
                </form>
                ';

                //Render "not found" message if no records were found.
                if ($resultContainer->get_mysqli_result()->num_rows==0){
                    echo '
                            <p width="100%" style="text-align: center;">Could not fetch Egg data".</p>
                    ';
                }
            }
            else {
                foreach ($resultContainer->getErrorMessages() as $errorMessage){
                    echo "<p>".$errorMessage."</p>";
                }
            }
        }




        public function CreateMoveTable(){
            // $name: Trainer's name
            // $action: URI to jump after hitting select user button. The action value to put in HTML form.
            $resultContainer = $this->notificationModel->getMoveNotifcations();
            if ($resultContainer->isSuccess()) {
                echo '
                <form action="search-notification.php" method="GET">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Select</th>   
                                <th scope="col">Date Created</th>
                                <th scope="col">Trainer</th>
                                <th scope="col">Pokemon</th>
                                <th scope="col">Old Move</th>
                                <th scope="col">New Move</th>
                                

                               
                            </tr>
                        </thead>
                        <tbody>
                    ';
                while ($row = $resultContainer->get_mysqli_result()->fetch_assoc()) {
                    echo '  <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="trainer" value="'.$row["notification_id"].'">
                                    </div>
                                </td>
                                <td>'.$row["date_created"].'</td>
                                <td>'.$row["trainer_name"].'</td>
                                <td>'.$row["breedname"].'</td>
                                <td>'.$row["old_move_name"].'</td>
                                <td>'.$row["new_move_name"].'</td>
                                
                            </tr>
                    ';
                }
                echo '
                        </tbody>
                    </table>';
                if ($resultContainer->get_mysqli_result()->num_rows!=0){
                    echo '
                        <input type="submit" value="Select trainer">
                    ';
                }
                echo '
                </form>
                ';

                //Render "not found" message if no records were found.
                if ($resultContainer->get_mysqli_result()->num_rows==0){
                    echo '
                            <p width="100%" style="text-align: center;">Could not fetch Move data".</p>
                    ';
                }
            }
            else {
                foreach ($resultContainer->getErrorMessages() as $errorMessage){
                    echo "<p>".$errorMessage."</p>";
                }
            }
        }




        public function CreateFightTable(){
            // $name: Trainer's name
            // $action: URI to jump after hitting select user button. The action value to put in HTML form.
            $resultContainer = $this->notificationModel->getFightNotifcations();
            if ($resultContainer->isSuccess()) {
                echo '
                <form action="search-notification.php" method="GET">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Select</th>   
                                <th scope="col">Date Created</th>
                                <th scope="col">Trainer</th>
                                <th scope="col">Pokemon</th>
                                <th scope="col">Description</th>

                               
                            </tr>
                        </thead>
                        <tbody>
                    ';
                while ($row = $resultContainer->get_mysqli_result()->fetch_assoc()) {
                    echo '  <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="trainer" value="'.$row["notification_id"].'">
                                    </div>
                                </td>
                                <td>'.$row["date_created"].'</td>
                                <td>'.$row["trainer_name"].'</td>
                                <td>'.$row["breedname"].'</td>
                                <td>'.$row["fight_description"].'</td>
                            </tr>
                    ';
                }
                echo '
                        </tbody>
                    </table>';
                if ($resultContainer->get_mysqli_result()->num_rows!=0){
                    echo '
                        <input type="submit" value="Select trainer">
                    ';
                }
                echo '
                </form>
                ';

                //Render "not found" message if no records were found.
                if ($resultContainer->get_mysqli_result()->num_rows==0){
                    echo '
                            <p width="100%" style="text-align: center;">Could not fetch Fight data".</p>
                    ';
                }
            }
            else {
                foreach ($resultContainer->getErrorMessages() as $errorMessage){
                    echo "<p>".$errorMessage."</p>";
                }
            }
        }



































 /*       
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
        }*/
    }
?>