<?php

    class CommonView { 
        private $page_links;
        public function __construct() {
            // Set the title and link to show on navbar in the format
            // "Title" => "path/to/page.php"
            $this->page_links = array(
                "Trainer" => "./trainer-search.php",
                "Notifications" => "#",
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
    }
?>