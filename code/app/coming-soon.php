<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
    ?>
    <?php   
        $commonView = new CommonView();
        // Replace the page title here. It generates title and CSS & JavaScript links.
        $commonView->header("Coming Soon!"); 
    ?>
    <style type="text/css">
      body { text-align: center; }
      /* h1 { font-size: 40px; } */
      #article { font: 20px Helvetica sans-serif; color: #333; display: block; text-align: left; width: 650px; margin: 0 auto; }
      a { color: #dc8100; text-decoration: none; }
      a:hover { color: #333; text-decoration: none; }
    </style>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%; margin-right:5%; margin-top: 100px;">
    <div id="article">
    <h1>"It’s not a bug – it’s an undocumented feature." -  Anonymous</h1>
    <div>
    <p>We apologize for the inconvenience, but we're performing some maintenance. </a>. We'll be back up soon...maybe!</p>
    <p>&mdash; Team MAJic</p>
    </div>
    </div>
    </div>
</body>
</html>