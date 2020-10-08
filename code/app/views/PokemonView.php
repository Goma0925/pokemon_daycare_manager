<?php
    include 'models/PokemonModel.php';

    class PokemonView { //Make sure to use plural noun for the class name
        private $pokemonModel;
        public function __construct() {
            $this->pokemonModel = new PokemonModel();
        }

        public function pokemonSelectionTableByTrainer(int $trainer_id,  bool $show_active, string $action, string $method, Array $form_params=Array()){
            // Renders a particular set of pokemon in table.
                //  $trainer_id: The ID of owner trainer of the pokemon you intend to get. 
                //      $action: The "action" value in HTML form. Determines where to send the form request.
                // $show_active: If set true, it will select the trainer's pokemon that are "active",
                //               meaning they are in daycare.
                // $form_params: An array of (name, value) pairs of form parameters to send with the HTML form.
            $resultContainer = $this->pokemonModel->getPokemonByTrainer($trainer_id, null, null, $show_active);
            $this->pokemonSelectionTable($resultContainer, $action, $method, $form_params);
        }

        public function pokemonSelectionTableAll(bool $show_active, string $action, string $method, Array $form_params=Array()){
            // Renders a particular set of pokemon in table. 
            // It renders all the pokemon that are either inactive or active.
                //      $action: The "action" value in HTML form. Determines where to send the form request.
                // $show_active: If set true, it will select the trainer's pokemon that are "active",
                //               meaning they are in daycare.
                // $form_params: An array of (name, value) pairs of form parameters to send with the HTML form.
            $resultContainer;
            if ($show_active){
                $resultContainer = $this->pokemonModel->getAllActivePokemon();
            }else{
                $resultContainer = $this->pokemonModel->getAllInactivePokemon();
            }
            $this->pokemonSelectionTable($resultContainer, $action, $method, $form_params);
        }

        private function pokemonSelectionTable(ResultContainer $resultContainer, string $action, string $method, Array $form_params){
            if ($resultContainer->isSuccess()){
                echo '
                <form action="'.$action.'" method="'.$method.'">';

                //Render hidden input based on $form_params
                foreach ($form_params as $name=>$value){
                    echo '
                    <input type="hidden" name="'.$name.'" value="'.$value.'">
                    ';
                };
                
                echo '
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
                                        <input class="form-check-input" type="radio" name="pokemon" value="'.$row["pokemon_id"].'" required>
                                    </div>
                                </td>
                                <td>'.$row["nickname"].'</td>
                                <td>'.$row["breedname"].'</td>
                            </tr>
                    ';
                }
                if ($resultContainer->get_mysqli_result()->num_rows!=0){
                    echo '  
                            <tr>
                                <td colspan="3"><button type="submit" style="float: right;margin-right:20px;" class="btn btn-info">Select</button></td>
                            </tr>
                    ';
                }
                echo '
                        </tbody>
                    </table>
                </form>';
    
                //Render "not found" message if no records were found.
                if ($resultContainer->get_mysqli_result()->num_rows==0){
                    echo '
                            <p width="100%" style="text-align: center;">No matching pokemon found for the trainer.</p>
                    ';
                }

            }
            else{
                foreach ($resultContainer->getErrorMessages() as $errorMessage){
                    echo "<p>".$errorMessage."</p>";
                }
            }
        }
    }
?>