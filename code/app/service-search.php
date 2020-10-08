<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            include_once 'views/ServiceRecordsView.php';
            include_once 'controllers/ServiceRecordsContr.php';
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
        
        <form action="service-search.php" method="GET"> 
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
            $serviceRecordsView = new ServiceRecordsView();
            $serviceRecordsContr= new ServiceRecordsContr();
            
            if (isset($_GET["search-by"]) && isset($_GET["status"])) {
                switch ($_GET["status"]) {
                    case "0":     
                        echo '<svg width="100px" height="25px" xmlns="http://www.w3.org/2000/svg">
                                <text x="30" y="15" >Inactive</text>
                                <circle cx="10" cy="10" r="10" fill="red" />
                             </svg>
                            ';
                    break;
                    case "1":
                        echo '<svg width="100px" height="25px" xmlns="http://www.w3.org/2000/svg">
                            <text x="30" y="15">Active</text>
                            <circle cx="10" cy="10" r="10" fill="green" />
                        </svg>
                        ';
                    break;
                    case "2":
                        echo '<svg width="100px" height="25px" xmlns="http://www.w3.org/2000/svg">
                                <text x="30" y="15" >All</text>
                                <circle cx="10" cy="10" r="10" fill="black" />
                            </svg>
                            ';
                    break;
                }

                if (isset($_GET["desired-value"])) {
                        $by = $_GET["search-by"];
                        $value = $_GET["desired-value"];
                        $status = $_GET["status"];
                        $serviceRecordsView->buildServiceRecordsTable('service-search.php',
                        $by, $value, $status);
                }
                else {
                    $by = $_GET["search-by"];
                    $status = $_GET["status"];
                    $serviceRecordsView->buildServiceRecordsTable('service-search.php',
                    $by, null, $status);
                }
            }
            
            if (isset($_POST["start"]) && isset($_POST["end"]) && isset($_POST["service_id"])) {
                $res = $serviceRecordsContr->updateServiceRecord($_POST["start"],$_POST["end"],$_POST["service_id"]);
            }
    
        ?>
    
    </div>
</body>
</html>

