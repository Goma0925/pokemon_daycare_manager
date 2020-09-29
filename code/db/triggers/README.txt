We write triggers and include them in the master script. 
We use triggers to handle side effects.

These help us accomplish business rules on the database level. 
Specifically, these business rules typically refer to domino-like events.

A trigger is not called by a script. Rather a trigger is defined on a table action. 
For instance, if we want to create an invoice everytime we create a customer, 
we would add an insertion trigger on the customer table and then have a statement in that
trigger that inserts into the invoice table. 
Note the example is psuedocode, not actual syntax. 

Ex: 
TRIGGER ON INSERT Customer 
-- body --
    (customer will be inserted, do not explicitly do it)
    insert into invoice table
-- end of body --

Note, the insertion of the customer into customer will be done.
We do not perform the insert in the body because the trigger 
merely intercepts an existing insertion. 

Here is an analogy. 
An action on a database is compared to a row of dominoes - 
you knock over the first one and rest come tumbling down in a chain reaction. 
This is similar to a TRIGGER which will cause further events on other tables and so on!


You may wonder when we would use a stored procedure vs a trigger. 
This is from what I have learned (michael). 

1. When doing a conditional insertion, deletion, or update, 
do them inside of a stored procedure because a trigger will always insert, delete, or update 
(unless we explicitly declare signals to stop the trigger event altogether, 
which is probably overkill). 

2. When we are going to perform the action but it also should performs other actions 
such as delete, update, insert, use a trigger. 

3. Because a trigger is almost a special case of stored procedure, 
it can replace a trigger 
but only if we call the stored procedure (which performs
the insertion, deletion, or update) from the application 
instead of INSERT, DELETE, UPDATE directly. 

Ex: 
PROCEDURE insert_trainer(some args...)
    (perhaps a start of transaction)
    insert trainer statement
    insert pokemon statement
    insert invoice statement
    (perhaps an end of transaction)
END OF PROCEDURE

Then from application
CALL insert_trainer(some args...).
On the other hand, the trigger could be used
in this case. Define a trigger on INSERT INTO Trainers
and then in the body of the trigger add insert pokemon and invoice. 
Then when you insert into Trainers, the trigger will accomplish the 
same thing. Look below.

TRIGGER ON INSERT Trainer
-- body --
    insert pokemon 
    insert invoice
-- end of body --

-----------------------------------------
Additionally, stored procedures can have transactions while triggers cannot. 
By default, the database system will execute a COMMIT after every statement, 
but a transaction says, do not execute COMMIT until all of these statements have been successfully completed.

Questions :

Trigger compared to a transaction

https://dba.stackexchange.com/questions/207916/difference-between-a-trigger-and-a-transaction/207929#:~:text=Transactions%20are%20for%20grouping%20actions,table)%20occurs%20in%20the%20database.

Trigger compared to stored procedure
https://stackoverflow.com/questions/16628484/sql-differences-between-stored-procedure-and-triggers#:~:text=We%20can%20execute%20a%20stored,as%20input%20to%20a%20trigger.
