<?php

    class CommonView { 
        private $page_links;
        public function __construct() {
            // Set the title and link to show on navbar in the format
            // "Title" => "path/to/page.php"
            $this->page_links = array(
                "Trainer" => "search-trainer.php",
                "Notifications" => "search-notification.php",
                "Service" => "#",
            );
        }

        public function navbar(){
            echo '
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <a class="navbar-brand" href="#">Pokemon Daycare</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">';

            //Render menu buttons
            foreach($this->page_links as $title => $link) {
                echo '
                        <li class="nav-item active">
                            <a class="nav-link" href="'.$link.'">'.$title.'<span class="sr-only">(current)</span></a>
                        </li>
                ';
            }
            
            echo '
                    </ul>
                </div>
            </nav>
            ';
        }

        public function header($page_title){
            echo '
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>'.$page_title.'</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>   
            ';
        }
    }
?>