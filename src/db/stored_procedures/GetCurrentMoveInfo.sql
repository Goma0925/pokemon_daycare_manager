CREATE VIEW CurrentMoveInformation AS
SELECT CurrentMoves.move_name, Moves.move_description
FROM CurrentMoves
INNER JOIN Moves USING move_name
WHERE pokemon_id = _pokemon_id;

/* Procedure */
DELIMITER //
CREATE PROCEDURE
    `GET_CURRENTMOVEINFO`(
        IN _pokemon_id INT
    )
    BEGIN
        SELECT CurrentMoves.move_name, Moves.move_description
        FROM CurrentMoves
        INNER JOIN Moves USING move_name
        WHERE pokemon_id = _pokemon_id;        
    END; //
DELIMITER ;