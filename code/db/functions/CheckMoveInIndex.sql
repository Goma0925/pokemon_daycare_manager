DELIMITER //
CREATE FUNCTION check_move_in_index(move_name VARCHAR(20)) 
  RETURNS INT 
  BEGIN
    RETURN COUNT(SELECT move_name FROM Moves);
  END //
DELIMITER ;
