/* Procedure */
DELIMITER //
CREATE PROCEDURE
    `INSERT_CURRENTMOVE`(
        IN _move_name VARCHAR(20),
        IN _pokemon_id INT,
        IN _move_description VARCHAR(150)=NULL, /** when move does not exist in Moves **/
        IN _old_move_name VARCHAR(20)=NULL /** when user already has 4 moves **/
    )
    BEGIN
        
        /** PREPARE ERROR STATEMENTS **/ 
        DECLARE err_max_without_replace CHAR(100); 
        SET err_max_without_replace = 'Pokemon has 4 moves and replacement
        move not specified.';
        
        DECLARE err_new_move_no_desc CHAR(200); 
        SET err_max_without_replace = 'You have specified a new move
        and did not provide its description.';
        
        DECLARE err_pokemon_not_exist CHAR(100); /* ALSO ENFORCE Pokemon Exists at App */
        SET err_pokemon_not_exist = 'The pokemon entered does not exist.'
        

        /** CHECK POKEMON EXISTENCE **/
        IF (check_pokemon_exists(_pokemon_id))
        THEN 
            /** CHECK MOVE EXISTENCE IN MOVE INDEX **/
            IF (check_move_in_index(_move_name)) = 1
            THEN
                /** MOVE ALREADY EXISTS IN INDEX; DO NOT ADD TO MOVES TABLE **/
                /** CHECK IF POKEMON ALREADY HAS 4 MOVES **/
                SELECT 'Specified move found in index';
                IF (num_moves_for_pokemon(_pokemon_id)) < 4
                    /** INSERT THIS NEW MOVE INTO CURRMOVES **/
                    INSERT INTO daycare.CurrentMoves(move_name, pokemon_id)
                    VALUES (_move_name, _pokemon_id);
                ELSE 
                    /** A MOVE NEEDS TO BE REPLACED **/
                    IF (_old_move_name IS NOT NULL)
                        /** REPLACEMENT MOVE SPECIFIED **/
                        /** UPDATE REPLACEMENT MOVE TO NEW MOVE **/ 
                    ELSE
                        /** REPLACEMENT MOVE NOT SPECIFIED **/
                        SELECT @err_max_without_replace; /** ENFO
                    END IF;
                
                END IF;
            ELSE
                SELECT 'Specified move not found in our index. 
                        Adding it to our index';
                IF (_move_description IS NOT NULL)
                THEN 
                    /** ADD NEW CURRENT MOVE TO MOVES **/
                    INSERT INTO daycare.Moves(move_name, move_description)
                    VALUES (_move_name, _move_description);
                ELSE 
                    SELECT @err_new_move_no_desc; /** ALSO ENFORCED AT APP LEVEL **/
            END IF; 
        ELSE
            SELECT @err_pokemon_not_exist; 
        END IF;
    END; //
DELIMITER ;