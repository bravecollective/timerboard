# BraveCollective TimerBoard

This timerboard is designed to integrate directly with the eve static data dump and provide a robust timer tracking dashboard.

## Installation

### Requirements

* PHP 5.4.4 or greater
* Mysql 5.5.34 or greater
* Nginx 1.4.1 or greater

### Steps to install

* Configure nginx for PHP-Fpm Access. Look at a guide like this to get PHP + Nginx working correctly: http://www.rackspace.com/knowledge_center/article/installing-nginx-and-php-fpm-setup-for-nginx)
* Checkout the repo to your desired location
* Create a nginx server file that points the root to the repo's public folder.
* Create 2 MySQL Databases, one for the timer board and one for the Eve Static Data Dump.
* Make a MySQL user that has global privileges on both databases and put the connection info in the app/config/database.php file.
* Import the MySQL static data dump from (https://www.fuzzwork.co.uk/dump/) into one of the databases.
* &nbsp; &nbsp; &nbsp; &nbsp; The Eve Static Data database must be the connection that's named eve_data. The current latest version is Rubicon 1.1 (1.1.94321) (https://www.fuzzwork.co.uk/dump/mysql56-rubicon-1.1-94321.tbz2)
* In a MySQL Database Manager, add standard indexes to all the fields in the 'mapdenormalize' table (see example SQL file: add_indexes.sql).
* Configure the app/config/database.php file with your database connection details.
* Edit app/database/seeds/UserTableSeeder.php and setup your default username, email address and password
* Edit app/config/app.php and put your domain name in the url property
* Run "chmod 0755 setup update" and then "./setup" from the folder the repo was checked out in.
* Make sure you see no errors, go to the domain name in a browser and try to login!

### Upgrading

* Run "./update" from the repo root to pull the latest version, all the latest packages, and migrate the DB (THis d.

### Contributing To TimerBoard

Please fork the repo and submit your changes with a pull request :)

## License

Brave Collective Core Services has been released under the MIT Open Source license.  All contributors agree to transfer ownership of their code to Matthew Glinski for release under this license.  (This is to mitigate issues in the future.)

### The MIT License

Copyright (C) 2014 Matthew Glinski and contributors.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.