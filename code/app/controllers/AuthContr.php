<?php 
    include_once 'models/AuthModel.php';
    include_once 'utils/ResultContainer.php';

    class AuthContr {
        private $authModel;
        public function __construct() {
            $this->authModel = new AuthModel();
        }

        public function login(string $username, string $password){
            $resultContainer = $this->authModel->login($username, $password);
            if ($resultContainer->isSuccess()){
                session_start();
                $_SESSION["authenticated"]= true;
            }else{
                $resultContainer->setFailure();
            }
            return $resultContainer;
        }

        public function logout(){
            session_start();
            if (isset($_SESSION)){
                session_destroy();
                $_SESSION = [];
            }
            return new ResultContainer();
        }
    }

?>