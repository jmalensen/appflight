# Procedure to follow to setup project

1- Git clone the repo

2- Copy .env.example to .env (usually in a git repo, informations about the DB would not be in the .env.example file)

3- Install docker and make sure the file develop.sh has correct rights (chmod 745 or chmod 744 develop.sh)

4- Type `./develop.sh up -d` to prepare the required docker containers

5- Once all docker are started, you can check if they are running properly with:
`./develop.sh ps`

6- Type `./develop.sh composer install` to install everything needed from composer.json

7- Type `./develop.sh artisan key:generate` to generate a key and copy it to the .env file, ensuring that user sessions and encrypted data remain secure

8- Type `./develop.sh artisan config:cache` to cache these settings into a file, which will boost the application’s load speed

9- Type `./develop.sh bash dblarav` to access the bash in the db container

## Once in the bash:

10- In # type `mysql -u root -p` and input the password of the root

## Once in the mysql:

`CREATE USER 'flightuser'@'localhost:8090' IDENTIFIED BY 'fl1ghtus3r3';`
`GRANT ALL ON laravdb.* TO 'flightuser'@'localhost:8090';`
`FLUSH PRIVILEGES;`
`EXIT;`
11- In mysql> type `show databases;` to see the list of databases
12- In mysql> type `CREATE USER '<user>'@'<host>' IDENTIFIED BY '<password>';` with correct user, host and password
13- In mysql> type `GRANT ALL ON <db>.* TO '<user>'@'<host>';` with correct db, user, host
14- In mysql> type `FLUSH PRIVILEGES;` to flush the privileges to notify the MySQL server of the changes
15- In mysql> type `EXIT;` to exit mysql command
16- In # type `exit` to exit bash command

## Prepare the db

17- Type `./develop.sh artisan migrate` to migrate the default Laravel tables
`./develop.sh artisan migrate:fresh` to drop all tables and do all migrations again

18- Populate the flights database with this seeder
`./develop.sh artisan db:seed --class=FlightSeeder`
