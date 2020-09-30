DELIMITER // 
CREATE TRIGGER IF NOT EXISTS 
    insert_fight_events BEFORE INSERT ON daycare.FightEvents FOR EACH ROW
BEGIN
    INSERT
    DECLARE fight_id INT;
    SELECT LAST_INSERT_ID() INTO fight_id; 

    INSERT INTO daycare.Fights()

END //
DELIMITER ; 
