INSERT INTO ServiceRecords (start_time, end_time, pokemon_id, trainer_id)
    VALUES 
        ('2020-09-26', NULL, 1, 1, "2020-09-26"), -- PikaPingPong is in daycare.
        ('2020-09-21', NULL, 2, 2, ), -- Polly was picked up on 2020-09-23
        ('2020-09-27', NULL, 3, 3, ); -- JJ is in daycare now.
        
'2020-09-25' '2020-09-27' --> '2020-09-22'
'2020-09-27' '2020-09-27' --> '2020-09-22'
'2020-10-04' '2020-10-02' --> '2020-10-00'
'2020-09-27' '2020-09-27' --> '2020-09-22'
'2020-09-21'  --> '2020-08-17'


