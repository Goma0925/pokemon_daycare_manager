Stored procedures can be written here
and then be included in the master script.
-------------------------------------------------------
Each stored procedure should have a descriptive name.
https://www.mssqltips.com/sqlservertutorial/169/naming-conventions-for-sql-server-stored-procedures/


Below is a short summary of what we will do

1. Name stored procedure based on actions
Insert
Delete
Update
Select
Get
Validate
etc...

2. Specify the object of the action
ex: InsertPokemon 

All in all,

[ACTION][OBJECT].sql

--------------------------------------------------------
When writing stored procedures, 
prefix the name with the table name. 
This is in the declaration of the stored procedure, 
not apart of naming convention.

ex: Pokemon.InsertPokemon

--------------------------------------------------------
When to use stored procedure?

Stored procedures can used for various reasons. 
Instead of putting logic inside of application,
we can often write it in the form of stored procedures.

For instance, if we want to check determine if we should
make an insertion based on some condition, then we could
have a stored procedure that checks a condition and if it 
is true, then insert, or false, not insert. This procedure
would probably be called from some model or controller (in the application).
We could also supply a message via an OUT parameter to indicate success or 
failure and also to inform the user. 

Look at the read me of the  sibling directory 'triggers'.
In that readme, the use of stored procedures is outlined
in comparison to triggers. 


