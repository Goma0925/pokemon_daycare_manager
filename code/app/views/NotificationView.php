<?php
    include_once 'models/NotificationModel.php';
    include_once 'views/MoveIndexView.php';

    class NotificationView {
        private $notificationModel;
        public function __construct() {
            $this->notificationModel = new NotificationModel();
        }


         // used to create the All Events Table
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
                        <input type="submit" value="Delete Record" name="delRec">
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


         // used to create the Egg Event Table
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
                    <input type="submit" value="Delete Record" name="delRec">
                    <input type="submit" value="Update Pickup" name="pickupEgg">
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



         // used to create the Move Event Table
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
                    <input type="submit" value="Delete Record" name="delRec">
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



        // used to create the Fight Event Table
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
                    <input type="submit" value="Delete Record" name="delRec">
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



































        // create the EggEvent form in register-notification.php
        public function eggeventRegistrationForm(){
            echo '<br><br>
            <form action="" method="post">

                <div class="row">
                    <div class="col">
                        <p>Date <input type="datetime-local" class="form-control" name="eventdatetime" placeholder="Date and Time" required></p>
                    </div>
                    <div class="col">
                        <p>Parent 1: <input type="number" class="form-control" name="parent1" placeholder="Pokemon ID" required></p>
                    </div>
                    <div class="col">
                        <p>Parent 2: <input type="number" class="form-control" name="parent2" placeholder="Pokemon ID" required></p>
                    </div>
                </div>
                <input type="submit">
            </form>
            ';
        }

        
        // create the FightEvent form in register-notification.php
        public function fighteventRegistrationForm(){
            echo '<br><br>
            <form action="" method="post">

                <div class="row">
                    <div class="col">
                        <p>Date <input type="datetime-local" class="form-control" name="eventdatetime" placeholder="Date and Time" required></p>
                    </div>
                    <div class="col">
                        <p>Pokemon ID: <input type="number" class="form-control" name="pokemon" placeholder="Pokemon ID" required></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p>Description of Event: <input type="text" class="form-control" name="description" placeholder="Fight Description" required></p>
                    </div>
                </div>
                <input type="submit">
            </form>
            ';
        }








        public function moveeventRegistrationForm(){
           
            echo '<br><br>
                <form action="register-notification.php" method="get">
                        
                    <div class="row">
                        <div class="col">
                            <p>Date <input type="datetime-local" class="form-control" name="eventdatetime" placeholder="Date and Time" required></p>
                        </div>
                        <div class="col">
                            <p>Pokemon ID: <input type="number" class="form-control" name="pokemon" placeholder="Pokemon ID" required></p>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col">
                        <p>';
                        //$MoveIndexView->moveDropdownBox("move", 1); 
                        echo '</p>
                        </div>
                    </div>
                    <input type="submit" name="chooseMove">
                </form>';                                //Render move dropdown boxes     
            }



        public function changeMoves($id){     
            $MoveIndexView = new MoveIndexView();       
            echo '<br><br>
            <form action="" method="post">
                    
                <div class="row">
                    <div class="col"><p> New Move:';
                    $MoveIndexView->moveDropdownBox("move", 1); 
                    echo '</p>
                    </div>
                    <div class="col">
                    </div>
                    </div>
                    <div class="row">
                    <div class="col">
                    <p>Current Moves: ';
                    $MoveIndexView->currentMoveDropdown($id); 
                    echo '</p>
                    </div>
                </div>
                <input type="submit">
            </form>';  }

        // general success message
        public function registrationSuccessMessage(){
            echo "<p>Successfully registered a new event!</p>";
        }
    }
?>