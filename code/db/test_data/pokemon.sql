USE daycare;

INSERT INTO Pokemon (trainer_id, current_level, nickname, breedname)
    VALUE 
        (1, 3, "PikaPingPong", "Pikachu"),
        (2, 10, "Polly", "Charamander"),
        (3, 31, "JJ", "Pichu");

INSERT INTO Moves (move_name, move_description)
    VALUE 
        ("Lightning Rod", "Lightning Rod forces all single-target Electric-type moves - used by any other Pokémon on the field - to target this Pokémon, and with 100% accuracy."),
        ("Blaze", "Blaze increases the power of Fire-type moves by 50% (1.5×) when the ability-bearer's HP falls below a third of its maximum"),
        ("Fire Punch", "Fire Punch deals damage and has a 10% chance of burning the target.");

INSERT INTO CurrentMoves (move_name, pokemon_id)
    VALUE 
        ("Blaze", 1),
        ("Lightning Rod", 2),
        ("Lightning Rod", 3);