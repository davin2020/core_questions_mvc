# Wellbeing Tracker (GP-CORE Questions Form)

## About
This is a web form where you can submit answers to the 'GP-CORE Questions', which is used to report subjective wellbeing. Results are plotted on a graph so you can see your progress over time

**This repo is very much a work in progress, with features in branches that aren't ready for `main` yet**

NB: CORE forms are owned, created & copyrighted by -
© CORE System Trust: https://www.coresystemtrust.org.uk/copyright.pdf

## Tech Stack
This project uses PHP 7, the Slim 4 framwork and MVC pattern (Routing, the Dependency Injection Container, Factories, Controllers & Views) to implement CRUD funtionality, with a MySQL database.

## Live Demo
This Wellbeing Tracker is live here (as of 31 August 2021) - https://wellbeing.2020-davin.dev.io-academy.uk/

## End User Features
This is a summary of the main changes to the app -
- View a list of existing users (5 April)
- Add a new user to the DB (5 April)
- View all existing CORE Questions (5 April)
- Answer the Core Questions and submit the form to save the details to the DB (8 April)
- View user history data ie previous dates & scores (9 April)
- View dynamic graph of previous dates & scores (11 April)
- Improved layout of CORE Questions form, so Answer options now move below each Question on smaller screens (26 April)
- Improved formatting of Question Form, added all GP-CORE questions to DB and added copyright info for Core Systems Trust (2 May)
- Moved Question Form to its own page and calculated & saved Mean Score when form is submitted (11 May)
- Restructured the app so that User Registration and Login options are on the homepage. After logging in, the user is taken to the Dashboard page, where they can access the Questions or History pages (13 May)
- Added more user fields to DB (fullname, email, password). Created  functionality to Register & Login users, using hashed passwords (17 May)
- Added sessions to restrict logged in users to only access their own Dashboard data and added Logout button to destory sessions (14 June) 
- Updated pages so that if users try to manually change the URL and aren't logged in, they are redirected to the login page (3 Aug)
- Added sessions to the Show History and Question Form pages, so users can only access their own data (6 Aug)
- Updated all page titles to be Wellbeing Tracker (24 Aug)
- Improved formatting of User History by adding tickmarks to graph and displaying details within a table (30 Aug)
- Improved mobile responsiveness with better Media Queries and layout/formatting for Wellbeing Questions and Answers Form (22 Sept) **NEW!**

## Screenshots
Register User or Login: 

![Image of Register User or Login screen](/screenshots/core_questions_app_login.PNG)

User Dashboard:

![Image of User Dashboard](/screenshots/core_questions_app_dashboard.png)

Answer Wellbeing Questions (GP-CORE Form): 

![Image of Core Questions Form](/screenshots/core_questions_app_all_questions_v4.png)

User History Graph & Details:

![Image of User's History and Graph](/screenshots/core_questions_app_graph_improved.PNG)

## To Install & Run Locally
1. Clone repo locally & `cd` to directory
2. Run `composer install`
3. Create a new MYSQL database called `corelifedb`
4. Adjust the DB connection details in the file `app/settings.php` according to your local setup
5. Import the file `db/core_questions_db.sql` into your database and run it to create multiple tables and add some example data
6. Run this from a normal command prompt to start the app `composer start`
7. Access this url in your browser `http://localhost:8087/`
8. Register as a New User, or Login with an Existing User account
