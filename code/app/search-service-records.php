<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/ServiceRecordsView.php';
            // include_once 'controllers/SomeContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        // Replace the page title here. It generates title and CSS & JavaScript links.
        $commonView->header("Service Records"); 
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%;margin-right:5%; margin-top: 25px;">


        <h2>Service Record Search</h2>
        <br>
        <form method="GET"> 
            <label for="all-check">All</label>
            <input type="radio" id="all-check" name="status" value="2" checked>
            <label for="inactive-check">Inactive</label>
            <input type="radio" id="inactive-check" name="status" value="0">
            <label for="active-check"> Active</label>
            <input type="radio" id="active-check" name="status" value="1">
            
            <select name="search-by" id="criteria" class="btn btn-secondary">
                <option value="trainer_id">TrainerID</option>
                <option value="service_record_id">ServiceRecordID</option>
                <option value="pokemon_id">PokemonID</option>
            </select>
            <input type="text" name="desired-value">


            <input type="submit" value="Search">
            <a style="float:right; padding: 5px" href="./register-trainer.php" type="button" class="btn btn-secondary">Insert new record</a>
        </form>


    <?php 
        // Render table
        $serviceRecordsView = new serviceRecordsView();
    
        if (isset($_GET["desired-value"])) {
            $by = $_GET["search-by"];
            $value = $_GET["desired-value"];
            $status = $_GET["status"];
            $serviceRecordsView->buildServiceRecordsTable('search-service-records.php',
            $by, $value, $status);
        }
        else {
            $by = $_GET["search-by"];
            $status = $_GET["status"];
            $serviceRecordsView->buildServiceRecordsTable('search-service-records.php',
            $by, null, $status);

        }


        // if (isset($_GET["trainer-name"])){
        //     $name = $_GET["trainer-name"];
        //     //Render trainer table. This function matches all the trainers whose name contains partial/entire
        //     //string of the $name. It ignores the difference between lowercase and uppercase.
        //     $serviceRecordsView >init_serviceRecordsTable('search-service-records.php');
        // }

    ?>
    </div>
</body>
</html>