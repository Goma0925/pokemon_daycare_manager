<!DOCTYPE html>
<html lang="en">
<head>
    <?php   
            include_once 'utils/Settings.php';
            Settings::setup_debug(); //Custom function that contains debug settings
            include_once 'views/CommonView.php';
            // include_once 'views/SomeView.php';
            // include_once 'controllers/SomeContr.php';
    ?>
    <?php   
        $commonView = new CommonView();
        $commonView->header("Register Pokemon"); 
    ?>
</head>
<body>
    <?php 
        $commonView = new CommonView();
        $commonView->navbar();
    ?>
    <div style="margin-left:5%; margin-right:5%; margin-top: 25px;">
    <?php 
        
        echo '
        <div class="row justify-content-center">
        <div class="col-md-6">
        <div class="card">
        <header class="card-header">
            <h4 class="card-title mt-2">Register Pokémon</h4>
        </header>
        <article class="card-body">
        <form>
            <div class="form-group row">
                <label for="nickname" class="col-3 col-form-label">Nick name</label>
                <div class="col-9">
                <input class="form-control" type="text" value="" id="nickname">
                </div>
                <label for="species" class="col-4 col-form-label"></label>
                <small class="form-text text-muted">Nick name should be less than 17 characters.</small>
            </div>
            <div class="form-group row">
                <label for="species" class="col-3 col-form-label">Species</label>
                <div class="col-9">
                  <input class="form-control" type="text" value="" name="breedname" id="species">
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-3 col-form-label">Level</label>
                <div class="col-9">
                    <input class="form-control" type="number" name="level" value="1" min="1" max="100" id="level">
                </div>
                <label for="species" class="col-4 col-form-label"></label>
                <small class="form-text text-muted">Level should be between 1-100.</small>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block"> Register  </button>
            </div> <!-- form-group// -->      
        </form>
        </article> <!-- card-body end .// -->
        <div class="border-top card-body text-center"><a href="select-pokemon.php?active=false&redirect-to=check-in-confirmation&trainer='.$trainer_id.'">Go back and select from database</a></div>
        </div> <!-- card.// -->
        </div> <!-- col.//-->
        
        </div> <!-- row.//-->
        ';
    ?>
    </div>
</body>
</html>