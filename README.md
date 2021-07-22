# user-upload.php

CLI script that takes a CSV with users (email, name, surname) as an input and parses it into a User class then uploads the users to MySQL user table.

# Requirements
Usesr Upload reqeuires a Linux server, running PHP version: 7.2.x and MySQL database(e.g. MariaDB)

# Basic Usage
    php user_upload.php --file [users.csv] -u databaseUsername -p databasePassword -h databaseHost  
    $dbName in user_upload.php must be reassigned to the name of you database  
## Arguments  
        --create_table - must be run on initially before other commands. Creates a MySQL table named 'users' containing 'email', 'name', 'surname' cloumns. Drops any previously created 'user' tables and recreates the table on each execution  
        --file [csv file name] - this is the name of the CSV to be parsed  
        --create_table this will cause the MySQL users table to be built (and no further action will be taken)  
        --dry_run – this will be used with the --file directive in case we want to run the cript but not insert into the DB. All other functions will be executed, but the database won't be altered  
        -u – MySQL username  
        -p – MySQL password  
        -h – MySQL host  
