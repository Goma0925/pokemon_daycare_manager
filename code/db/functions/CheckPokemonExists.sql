DELIMITER //
CREATE FUNCTION check_pokemon_exists(_pokemon_id INT) 
  RETURNS TINYINT
  BEGIN
    IF EXISTS (SELECT pokemon_id FROM Pokemon 
              WHERE _pokemon_id = pokemon_id)
    THEN
        RETURN 1;
    ELSE 
        RETURN 0;
    END IF; 
  END //
DELIMITER ;
