# Poll App
This app was build using laravel and vue.
Each poll can have from two up to ten asnwers.
User can choose if poll should accept one or many answers.
User can also choose if each user can answer his poll only once.
This protection is based on IP duplicate protection.
Result of poll is public to everyone.

## Table of contents

1. [Technologies](#Requirements)
2. [Setup](#Setup)
3. [Licence](#Licence)

## Technologies
Technologies used during development of Poll App:
* PHP 8.0.11
* Composer
* Laravel 8.x
* MariaDB 10.4.21
* Node.JS 14.17.1
* NPM 6.14.13
* Vue 3.0
* Bootstrap 5

## Setup
Download the app using git:
```
$ git clone https://github.com/michalsamsel/poll-app.git
$ cd ./poll-app
```

To enable app:
```
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan migrate
$ php artisan serve

$ npm install
$ npm run prod
```

## Licence
[The MIT License](https://opensource.org/licenses/MIT)