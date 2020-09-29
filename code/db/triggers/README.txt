We write triggers and include them in the master script. 
We use triggers to handle side effects.

These help us accomplish business rules on the database level. Specifically, these business rules typically refer to domino-like events.

A trigger is not called by a script. Rather a trigger is defined on a table action. For instance, if we want to create an invoice anytime we create a customer, we wouldadd a trigger on insertion into customer table that alsoinserts into the invoice table.  

A rather nice analogy can be found here. An action on a database is compared to a row of dominoes - you knock over the first one and rest come tumbling down in a chain reaction. This is similar to a TRIGGER which will cause further events on other tables and so on!

You may wonder when we would use a stored procedure vs a trigger. This is from what I have learned (michael). When doing a conditional insertion or conditional deletion ror conditional update, do them inside of a stored procedure because a trigger will always insert, delete, or update (unless we explicitly declare signals to stop the trigger event altogether, which is probably overkill). When we are going to perform the action but it also should performs other actions, use a trigger. Additionally, stored procedures can have transactions while triggers cannot. By default, the database system will execute a COMMIT after every statement, but a transaction says, do not execute COMMIT until all of these statements have been successfully completed.

Questions :

Trigger compared to a transaction

https://dba.stackexchange.com/questions/207916/difference-between-a-trigger-and-a-transaction/207929#:~:text=Transactions%20are%20for%20grouping%20actions,table)%20occurs%20in%20the%20database.

Trigger compared to stored procedure
https://stackoverflow.com/questions/16628484/sql-differences-between-stored-procedure-and-triggers#:~:text=We%20can%20execute%20a%20stored,as%20input%20to%20a%20trigger.
