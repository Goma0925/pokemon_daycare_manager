<?php
    include 'models/PokemonModel.php';

    class PokemonView { //Make sure to use plural noun for the class name
        private $pokemonModel;
        public function __construct() {
            $this->pokemonModel = new PokemonModel();
        }

        public function pokemonSelectionTable($trainer_id, $action, $show_active){
            $resultContainer = $this->pokemonModel->getPokemonByTrainer($trainer_id, null, null, $show_active);
            if ($resultContainer->isSuccess()){
                echo '
                <form action="'.$action.'" method="POST">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Select</th>   
                                <th scope="col">Name</th>   
                                <th scope="col">Species</th>
                            </tr>
                        </thead>
                        <tbody>
                    ';
                while ($row = $resultContainer->get_mysqli_result()->fetch_assoc()) {
                    echo '  <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="pokemon" value="'.$row["trainer_id"].'">
                                    </div>
                                </td>
                                <td>'.$row["nickname"].'</td>
                                <td>'.$row["breedname"].'</td>
                            </tr>
                    ';
                }
                echo '
                        </tbody>
                    </table>';
                if ($resultContainer->get_mysqli_result()->num_rows!=0){
                    echo '
                        <input type="submit" value="Select pokemon">
                    ';
                }

                //Close the form + add the trainer ID to the form data.
                echo '
                    <input type="hidden" name="trainer" value="'.$trainer_id.'">
                </form>
                ';
    
                //Render "not found" message if no records were found.
                if ($resultContainer->get_mysqli_result()->num_rows==0){
                    echo '
                            <p width="100%" style="text-align: center;">No matching pokemon found for the trainer.</p>
                    ';
                }

            }else{
                foreach ($resultContainer->getErrorMessages() as $errorMessage){
                    echo "<p>".$errorMessage."</p>";
                }
            }
        }
    }
?>