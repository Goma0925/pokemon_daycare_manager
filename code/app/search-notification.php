<!DOCTYPE html>
<html lang="en">
<head>
    <?php     
        include_once 'utils/Settings.php';
        Settings::setup_debug(); //Custom function that contains debug settings
        include_once 'views/CommonView.php';
        include_once 'views/NotificationView.php';
        include_once 'controllers/NotificationContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        $commonView->header("Notifications Search");
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%;margin-right:5%; margin-top: 25px;">
        <p>Temporary message: Hello guys! check out the notifcations</p>
        <p> 
            <form action="select-pokemon.php" method="GET">
                <input type="hidden" value="true" name="active">
                <input type="hidden" value="all" name="trainer">
                <button type="submit" style="margin-left: 10px; float:right" value="fight-update" name="redirect-to" type="button" class="btn btn-secondary">Report Fight</button>
                <button type="submit" style="margin-left: 10px;float:right" value="egg-update" name="redirect-to" type="button" class="btn btn-secondary">Report Egg</button>
                <button type="submit" style="margin-left: 10px;float:right" value="move-update" name="redirect-to" type="button" class="btn btn-secondary">Report New Move</button>
            </form>
        </p>
        <h2>Latest Event Report</h2>
        <form method="GET">
            <p>Filter by: 
                <input type="submit" value="All" name="All-notifs">
                <input type="submit" value="Eggs" name="Egg-notifs">
                <input type="submit" value="Moves" name="Move-notifs">
                <input type="submit" value="Fights" name="Fight-notifs">
            </p>
        </form>

    <?php 
        
        // Render table
        $notificationView = new NotificationView();
        if (isset($_GET["All-notifs"])){
            
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name. It ignores the difference between lowercase and uppercase.
            $notificationView->CreateAllTable();
        }
        if (isset($_GET["Egg-notifs"])){
            
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name. It ignores the difference between lowercase and uppercase.
            $notificationView->CreateEggTable();
        }
        if (isset($_GET["Move-notifs"])){
            
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name. It ignores the difference between lowercase and uppercase.
            $notificationView->CreateMoveTable();
        }
        if (isset($_GET["Fight-notifs"])){
            
            //Render trainer table. This function matches all the trainers whose name contains partial/entire
            //string of the $name. It ignores the difference between lowercase and uppercase.
            $notificationView->CreateFightTable();
        }
        
    ?>
    </div>
</body>
</html>