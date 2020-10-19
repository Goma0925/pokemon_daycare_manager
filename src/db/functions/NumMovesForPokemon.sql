/** Returns the number of current moves a pokemon has) **/
DELIMITER //
CREATE FUNCTION num_moves_for_pokemon(pokemon_id INT) 
  RETURNS INT 
  BEGIN
    RETURN COUNT(SELECT pokemon_id FROM CurrentMoves);
  END //
DELIMITER ;
