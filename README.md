# Core Questions Form - Using PHP & MVC

## About
This will be a web form where you can submit answers to 'Core Questions'. It will have basic CRUD functionality, with a MYSQL db.

Cloned from an existing To Do app based on the Slim 4 framework.
Uses Routing, the Dependency Injection Container, Factories, Controllers & Views to implement CRUD funtionality

## Live Demo
This To Do app is live here - N/A

## End User Features - coming soon
- View a list of questions
- Answer questions and submit form
- View historical answers

## To Install & Run Locally
1. Clone repo locally & `cd` to directory
2. Run `composer install`
3. Create a new MYSQL database called `corelifedb`
4. Adjust the DB connection details in the file `src/DBConnector.php` according to your local setup
5. Import the file `db/corelifedb.sql` into your database and run it to create multiple tables and add some example data
6. Run this from a normal command prompt to start the app `php -S localhost:8080 -t public/ public/index.php`

