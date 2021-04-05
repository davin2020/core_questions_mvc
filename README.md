# Core Questions Form - Using PHP & MVC

## About
This will be a web form where you can submit answers to 'Core Questions'. It will have basic CRUD functionality, storing data in a MYSQL db.

Cloned from an existing To Do app based on the Slim 4 framework.
Uses Routing, the Dependency Injection Container, Factories, Controllers & Views to implement CRUD funtionality

## Live Demo
This To Do app is live here - (not yet)

## End User Features
- View a list of existing users
- Add a new user to the DB
- View all existing Core Questions
<!-- - Answer questions and submit form
- View historical answers -->

## To Install & Run Locally
1. Clone repo locally & `cd` to directory
2. Run `composer install`
3. Create a new MYSQL database called `corelifedb`
4. Adjust the DB connection details in the file `src/DBConnector.php` according to your local setup
5. Import the file `db/corequestions.sql` into your database and run it to create multiple tables and add some example data
6. Run this from a normal command prompt to start the app `php -S localhost:8080 -t public/ public/index.php`

