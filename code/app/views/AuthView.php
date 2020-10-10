<?php

    class AuthView {

        public function loginBox(string $action){
            echo '
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4" style="margin-top:50px;">
                            <div class="card">

                                <div class="card-header">
                                    <h2 class="text-center">Log in</h2>       
                                </div>
                                <div class="card-body">
                                    <form action="'.$action.'" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control" placeholder="Username" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control" placeholder="Password" required="required">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Log in</button>
                                        </div>
                                        <div class="clearfix">
                                            <!--  <label class="float-left form-check-label"><input type="checkbox"> Remember me</label>  -->
                                            <!--  <a href="#" class="float-right">Forgot Password?</a> -->
                                        </div>        
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>
            ';

        }

        public function loginWarning(){
            echo '
            <div class="alert alert-warning" role="alert">
                To see this page, you need to <a href="login.php">login</a>.
            </div>
            ';
        }

        public function logoutMessage(){
            echo '
            <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">See you!</h1>
                <p class="lead">You have been logged out. To login again, click <a href="login.php">here</a>.</p>
            </div>
            </div>
            ';
        }
    }
?>