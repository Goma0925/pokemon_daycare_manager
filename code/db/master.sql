DROP DATABASE IF EXISTS daycare;
CREATE DATABASE daycare;
USE daycare;

/* Create a table for 'Trainers' */
CREATE TABLE Trainers (
    PRIMARY KEY(trainer_id),
    trainer_id INT AUTO_INCREMENT, /* change from auto to something else */
    email VARCHAR(32) UNIQUE,
    phone VARCHAR(12) NOT NULL UNIQUE, 
    trainer_name VARCHAR(16) NOT NULL
);

/* Create a table for 'Pokemon' */
CREATE TABLE Pokemon (
    PRIMARY KEY(pokemon_id),
    pokemon_id INT AUTO_INCREMENT,
    trainer_id INT NOT NULL,
    current_level INT DEFAULT 1,
    nickname VARCHAR(25), /* changed from 16 to 25, same for below */
    breedname VARCHAR(25) NOT NULL,
    CONSTRAINT `trainer_fk` 
        FOREIGN KEY (trainer_id) 
        REFERENCES Trainers(trainer_id)
        /* add deletion rule */ 
);


/* Create a table for 'BusinessStates' */
CREATE TABLE BusinessStates ( /* EVEN THOUGH WE CURRENT PASS DATETIME TO date_changed, 
                              SQL just truncates and casts accordingly */
    PRIMARY KEY(date_changed),
    date_changed DATE NOT NULL, /*bstate_rule_date is a fk ref for this pk */
    /*time_changed TIME NOT NULL, /* only used for administration logs */
    price_per_day DECIMAL NOT NULL,
    max_pokemon_per_trainer INT NOT NULL,
    flat_egg_price DECIMAL NOT NULL
);

/* Create a table for 'ServiceRecords' */
CREATE TABLE ServiceRecords (
    PRIMARY KEY(service_record_id),
    service_record_id INT AUTO_INCREMENT,
    start_time DATETIME NOT NULL,
    end_time DATETIME, 
    pokemon_id INT,
    trainer_id INT, 
    bstate_rule_date DATE,
    CONSTRAINT `pokemon_id_fk`  /* add delete rules here */
        FOREIGN KEY (pokemon_id) 
        REFERENCES Pokemon(pokemon_id),
    CONSTRAINT `trainer_id_fk` 
        FOREIGN KEY (trainer_id) 
        REFERENCES Trainers(trainer_id),
    CONSTRAINT `b_id`
        FOREIGN KEY (bstate_rule_date) REFERENCES BusinessStates(date_changed) /* ADDED THIS IN ON OCT 09 */
);

/* Create a table for 'Ratings' */
CREATE TABLE Ratings ( /* is the service record id fk allow duplicates? */
    PRIMARY KEY(service_record_id),
    service_record_id INT UNIQUE,
    FOREIGN KEY (service_record_id)
    REFERENCES ServiceRecords(service_record_id), /* add deletion/update rules */
    satisfaction INT NOT NULL  
        CHECK (satisfaction >= 1 && satisfaction <= 5),
    comment VARCHAR(500),
    rating_date DATETIME NOT NULL
);

/* Create a table for 'Moves' */
CREATE TABLE Moves (
    PRIMARY KEY(move_name),
    move_name VARCHAR(20), /* length is what? update tables fields doc */
    move_description VARCHAR(150)
);

/* Create a table for 'CurrentMoves' */
CREATE TABLE CurrentMoves ( /* is this compound pk? */
    PRIMARY KEY(move_name, pokemon_id),
    move_name VARCHAR(20) NOT NULL, /* length is what? update tables fields doc */
    pokemon_id INT NOT NULL,
    FOREIGN KEY (move_name)
    REFERENCES Moves(move_name),
    FOREIGN KEY (pokemon_id)
    REFERENCES Pokemon(pokemon_id)
);

/* Create a table for 'Notifications' 
   Parent table for several subset [NOUN]Event tables */
CREATE TABLE Notifications (
    PRIMARY KEY(notification_id),
    notification_id INT AUTO_INCREMENT,
    trainer_id INT,
    date_created DATETIME NOT NULL,
    FOREIGN KEY (trainer_id)
    REFERENCES Trainers(trainer_id)
);

/* Create a table for 'EggEvents' */
CREATE TABLE EggEvents (
    PRIMARY KEY(notification_id),
    notification_id INT,
    father INT,
    mother INT CHECK (mother != father), /* mother and father cannot be same */
    given_to_trainer TINYINT DEFAULT 0,
    FOREIGN KEY (notification_id)
    REFERENCES Notifications(notification_id) 
    ON DELETE CASCADE,
    FOREIGN KEY (father) 
    REFERENCES Pokemon(pokemon_id),
    FOREIGN KEY (mother) 
    REFERENCES Pokemon(pokemon_id)
    
);

/* Create a table for 'MoveEvents' */
CREATE TABLE MoveEvents (
    PRIMARY KEY(notification_id),
    notification_id INT,
    old_move_name VARCHAR(20),
    new_move_name VARCHAR(20),
    pokemon_id INT,
    FOREIGN KEY (old_move_name)
    REFERENCES Moves(move_name),
    FOREIGN KEY (notification_id)
    REFERENCES Notifications(notification_id)
    ON DELETE CASCADE
   /* FOREIGN KEY (new_move_name, pokemon_id) /* CFK */
   /* REFERENCES CurrentMoves(move_name, pokemon_id) */
    
);


/* Create a table for 'Fights' */
CREATE TABLE Fights ( /* is this most recent? */
    PRIMARY KEY(fight_id),
    fight_id INT AUTO_INCREMENT,
    fight_description VARCHAR(500) NOT NULL /* different from specs */
);

/* Create a table for 'FightEvents' */
CREATE TABLE FightEvents ( /* is this most recent? */
    PRIMARY KEY(notification_id),
    notification_id INT,
    pokemon_id INT,
    fight_id INT, 
    FOREIGN KEY (notification_id)
    REFERENCES Notifications(notification_id)
    ON DELETE CASCADE,
    FOREIGN KEY (fight_id)
    REFERENCES Fights(fight_id),
    FOREIGN KEY (pokemon_id)
    REFERENCES Pokemon(pokemon_id)
);

/* VIEWS */
CREATE VIEW ActiveServiceRecords 
    AS
        SELECT service_record_id, start_time, end_time, pokemon_id,
        trainer_id
        FROM ServiceRecords
        WHERE end_time IS NULL; 

CREATE VIEW InactiveServiceRecords 
    AS
        SELECT service_record_id, start_time, end_time, pokemon_id,
        trainer_id
        FROM ServiceRecords
        WHERE end_time IS NOT NULL; 

CREATE VIEW ActivePokemon 
    AS
        SELECT Pokemon.pokemon_id, Pokemon.trainer_id, Pokemon.current_level,
        Pokemon.nickname, Pokemon.breedname 
        FROM Pokemon 
        INNER JOIN ServiceRecords
        USING (pokemon_id) WHERE ServiceRecords.end_time IS NULL
        GROUP BY pokemon_id; --Remove duplicates in case of bugs.


CREATE VIEW InactivePokemon 
    AS
        -- Pokemon that don't have any service records
        SELECT Pokemon.pokemon_id, Pokemon.trainer_id, Pokemon.current_level,
        Pokemon.nickname, Pokemon.breedname 
        FROM Pokemon 
        LEFT JOIN ServiceRecords
        USING (pokemon_id)
        WHERE ServiceRecords.service_record_id is NULL
        UNION 
        -- Pokemon that have service records but all of them are inactive.
        SELECT Pokemon.pokemon_id, Pokemon.trainer_id, Pokemon.current_level,
        Pokemon.nickname, Pokemon.breedname 
        FROM Pokemon 
        LEFT JOIN ServiceRecords
        USING (pokemon_id) WHERE ServiceRecords.end_time IS NOT NULL ;

