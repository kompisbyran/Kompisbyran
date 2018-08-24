[![Build Status](https://travis-ci.org/kompisbyran/Kompisbyran.svg)](https://travis-ci.org/kompisbyran/Kompisbyran)

Kompisbyrån
========================

> Kompisbyrån finns för att fler ska bli integrerade i samhället med hjälp av språket. Målet med Kompisbyrån är att fler människor ska bli bättre på svenska och på så vis lättare komma in i samhället. Därför länkar vi samman personer som vill öva sin svenska med personer som vill hjälpa någon att förbättra sin svenska. En kompisfika är ett kulturellt utbyte mellan personer med olika bakgrund, erfarenheter, intressen och åldrar.

Kompisbyrån finns på [www.kompisbyran.se](http://www.kompisbyran.se).


Setup
------------

### Installation

Install MySQL [OSX El capitan guide](http://wpguru.co.uk/2015/11/how-to-install-mysql-on-mac-os-x-el-capitan/)

Nice graphical admin tool for Mac [Sequelpro](http://sequelpro.com/)

Install PHP (requires PHP >= 5.6) [OSX using homebrew](http://blog.shameerc.com/2015/12/installing-php-7-on-mac-using-homebrew)

```bash
brew update
```
```bash
brew install homebrew/php/php70
```
```bash
export PATH="$(brew --prefix homebrew/php/php70)/bin:$PATH"
```

Install Composer [guide](https://getcomposer.org/doc/00-intro.md)

```bash
$ curl -sS https://getcomposer.org/installer | php
```
```bash
$ mv composer.phar /usr/local/bin/composer
```


### Setup 

Install required components

```bash
$ php composer.phar install
```

Setup cron jobs

```
php app/console kompisbyran:send-confirm-meeting-emails 21 0 #days since created and number of mails sent
php app/console kompisbyran:send-confirm-meeting-emails 35 1
php app/console kompisbyran:send-follow-up-email2 14 #days since user marked meeting as held
php app/console kompisbyran:send-follow-up-email3 150 #days since user marked meeting as held
php app/console kompisbyran:send-meet-again-emails 60 #days since connection was created
php app/console kompisbyran:send-emails-to-incomplete-users 2 #days since user was created
```

### Config

Set default or specify values for connection string (later found in app/config/parameters.yml)
There can be more than the ones specified below but they are not mandatory to setup locally.

```php
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: 8889
    database_name: kompisbyran
    database_user: root
    database_password: root
```

Create empty database with `php app/console doctrine:database:create`  
Create database schema with `php app/console doctrine:schema:create`  
Fill with fixture data with `php app/console doctrine:fixtures:load -n`

### Usage

```bash
$ php app/console server:run
```

Site is running at 127.0.0.1:8000


### Errors 

Make sure date.timezone is set in php.ini (ex. OSX etc/php.ini copy php.ini.default in it doesn't exist)

`date.timezone = "Europe/Amsterdam"`

