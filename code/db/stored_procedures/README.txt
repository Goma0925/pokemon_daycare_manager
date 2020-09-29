Stored procedures can be written here
and then be included in the master script.
-------------------------------------------------------
Each stored procedure should have a descriptive name.
https://www.mssqltips.com/sqlservertutorial/169/naming-conventions-for-sql-server-stored-procedures/


Below is a short summary of what we will do

1. Name stored procedure based on action
Insert
Delete
Update
Select
Get
Validate
etc...

2. Specify the object of the action

FULL EXAMPLE:
InsertPokemon

--------------------------------------------------------
When writing stored procedures, prefix the name with thetable name. This is in the declaration of the stored procedure, not the name.

Pokemon.InsertPokemon
