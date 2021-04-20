Drivers Co-op Server App
=======================

This is the main server app and API for the Drivers Co-op.

Dependencies
------------

* PHP 7.*
* MySQL 8.*
* [Composer](https://getcomposer.org)

If you're on MacOS, the easiest way to get all of this is with [Homebrew](https://brew.sh) by running `brew install php@7.3 mysql composer`.

Setup
-----

1. Clone this repo to your machine: `git clone git@github.com:driverscoop/app.git`.
2. `cd app`
3. `composer install`
4. Create a new MySQL database, however you normally do that.
5. `cp .env.example .env`, then edit `.env` with your database credentials and other settings.
6. Run `php artisan migrate` to install the database schema.
7. Run `php artisan serve` to run the app.

Currently, a lot of app settings are stored in the database, so depending on what you are testing or running, you might want to ask Jake for a copy of the prod or dev databases.

Deployment
----------

The app is deployed to AWS with Elastic Beanstalk. The deployment tasks and
settings are all included in this repo in the `.ebextensions`,
`.elasticbeanstalk`, and `.platform` directories. Talk to Jake to get set up in
that system.
