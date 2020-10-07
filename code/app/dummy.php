<!DOCTYPE html>
<html>
<body>

<h1>The select element</h1>

<p>The select element is used to create a drop-down list.</p>


<?php 
        if (isset($_GET["search-by"]) && isset($_GET["desired_value"])) {
            var_dump($_GET["search-by"]);
            var_dump($_GET["desired_value"]);

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
