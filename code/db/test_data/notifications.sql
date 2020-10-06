INSERT INTO Notifications (trainer_id, date_created)
    VALUES 
        (1,'2020-09-25'), -- Will be EggEvent
        (2,'2020-09-21'), -- Will be FightEvent
        (2,'2020-09-27'); -- Will be MoveEvent

SELECT * FROM Notifications;
