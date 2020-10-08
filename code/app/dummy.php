<!DOCTYPE html>
<html>
<body>

<h1>The select element</h1>

<p>The select element is used to create a drop-down list.</p>


<?php 
            include_once 'views/ServiceRecordsView.php';
            include_once 'controllers/ServiceRecordsContr.php';
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

            echo "cool";
            if (isset($_POST["res"])) {
                $res = $serviceRecordsContr->updateServiceRecord($_POST["res"]);
                var_dump($_POST["res"]); 
    
            }
    


        }
?>

  <form method="GET"> 
      <select name="search-by" id="criteria" >
          <option value="trainer_id">TrainerID</option>
          <option value="phone">Phone</option>
          <option value="name">Name</option>
          <option value="email">Email</option>
      </select>
      <input type="text" name="desired_value">
      <input type="submit" value="Search">
      <a style="float:right; padding: 5px" href="./register-trainer.php" type="button">Insert new record</a>
  </form>

<p>Click the "Submit" button and the form-data will be sent to a page on the 
server called "action_page.php".</p>

</body>
</html>
