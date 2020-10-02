<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC demo</title>
    <?php   ini_set('display_errors', 1);
            ini_set("display_startup_errors", 1);
            error_reporting(E_ALL);
    ?>
    <!-- Added Bootstrap CSS just because it's more fun :) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <?php include 'views/SomeView.php';?>
    <?php include 'controllers/SomeContr.php';?>
</head>
<body>
    <div style="margin-left:5%;margin-right:5%">
    <?php 
        // Code here
    ?>
    </div>
</body>
</html>