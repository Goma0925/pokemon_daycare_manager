INSERT INTO Notifications (trainer_id, date_created)
    VALUES 
        (1,'2020-09-25'), -- Will be EggEvent
        (1,'2020-09-21'), -- Will be FightEvent
        (1,'2020-09-27'); -- Will be MoveEvent


INSERT INTO EggEvents (notification_id, father, mother)
    VALUES 
        (1,1,5);

INSERT INTO MoveEvents (notification_id, old_move_name, new_move_name, pokemon_id)
    VALUES 
        (3,"Fire Punch","Blaze", 1);

INSERT INTO Fights (fight_description)
    VALUES 
        ("This is a fake description");


INSERT INTO FightEvents (notification_id, pokemon_id, fight_id)
    VALUES 
        (2,1,1);




