<!DOCTYPE html>
<html lang="en">
<head>
    <?php   include_once 'views/CommonView.php';
            include_once 'views/SomeView.php';
            include_once 'controllers/SomeContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        // Replace the page title here. It generates title and CSS & JavaScript links.
        $commonView->header("Page title not set"); 
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%; margin-right:5%; margin-top: 25px;">
    <?php 
        // Code here
        echo "Hello World!"
    ?>
    </div>
</body>
</html>