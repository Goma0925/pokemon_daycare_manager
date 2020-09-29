This directory houses the main build script alongside
any triggers and stored procedures used in the build script. 

------------------------------------------------------------
Below are some useful sources to read concerning different
sql topics that we will incorporate into our implementation. 

SQL TRANSACTIONS 
-- https://www.tutorialspoint.com/sql/sql-transactions.htm
-- https://mariadb.com/kb/en/acid-concurrency-control-with-transactions/

Atomicity
    All succeed or all fail
Consistency
	Ensures database properly changes states on committed transaction (success)
Isolation 
	Enables transactions to operate independently of and transparent to eachother
Durability	
	Result or effect persists (in case of system failure)

Does the sql server automatically commit each update, insert, delete? 
I looked into this and it does. However, using transaction, nothing will be commited 
by sql server until end of transaction with commit: 
https://stackoverflow.com/questions/19026602/do-we-need-to-execute-commit-statement-after-update-in-sql-server

Are they also used in stored procedures?
-https://stackoverflow.com/questions/9974325/mysql-transaction-within-a-stored-procedure 

Other useful readings. 
https://dba.stackexchange.com/questions/43254/is-it-a-bad-practice-to-always-create-a-transaction