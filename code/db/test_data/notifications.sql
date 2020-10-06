INSERT INTO Notifications (trainer_id, date_created)
    VALUES 
        (1,'2020-09-25'), -- Will be EggEvent
        (2,'2020-09-21'), -- Will be FightEvent
        (2,'2020-09-27'); -- Will be MoveEvent


INSERT INTO EggEvents (notification_id, father, mother)
    VALUES 
        (1,1,5);

INSERT INTO MoveEvents (notification_id, old_move_name, new_move_name, pokemon_id)
    VALUES 
        (3,"Fire Punch","Blaze", 1);

SELECT Notifications.date_created, 
        p.breedname,
        m.old_move_name,
        m.new_move_name,
        Trainers.trainer_name  
    FROM MoveEvents AS m
    INNER JOIN (Notifications) 
    ON (m.notification_id = Notifications.notification_id)
    INNER JOIN (Pokemon AS p)
    ON (p.pokemon_id = m.pokemon_id)
    INNER JOIN (Trainers)
    ON (Trainers.trainer_id = Notifications.trainer_id);
