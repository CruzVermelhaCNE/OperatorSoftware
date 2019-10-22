# Cruz Vermelha Portuguesa - Coordenação Nacional de Emergência

Applying Technology to Emergency

[![Build Status](https://travis-ci.org/CruzVermelhaCNE/OperatorSoftware.svg?branch=master)](https://travis-ci.org/CruzVermelhaCNE/OperatorSoftware)
[![Coverage Status](https://coveralls.io/repos/github/CruzVermelhaCNE/OperatorSoftware/badge.svg?branch=master)](https://coveralls.io/github/CruzVermelhaCNE/OperatorSoftware?branch=master)

## Project Objectives

This Operator Software will be used by our Operations Room operators.

### Users

There are two types of users:

* Operator
* Manager

Operators will be able to do the following:

* Connect with Flash Operator Panel 2 on specific extensions - Link defined in .env
* Access Missed Calls and Calls that are necessary to return
* Mark Missed Call and Calls To Return as completed
* Fetch Report from when he logged in to that point

Managers will be able to do the following:

* Change Extensions of Operator
* Create Users
* Change User Types (Operator/Manager)
* Fetch Reports by operator or global on selected dates

All users need to perform basic tasks such as:

* Change Password
* Recover Lost Password

A user can be a Manager and an Operator.

### Missed Calls and Calls To Return

This calls are flagged on Asterisk CDR database.

### Flash Operator Panel

This page needs to be integrated with an iframe.

### Analytics

The following information needs to be generated on the reports:

* Average time per call
* Missed calls and their duration
* Time spent with the FOP iframe open
* Which operator picked up a call
* Which operator missed calls

### Dashboard

The dashboard needs to be intuitive and fast to use.


## Project setup

The easiest way to get the API started is through Artisan Serve

### Command Line Interface

In order to run commands (`composer`, `artisan`, ...) in the **API** container, log into it via:

Once the infrastructure is running for the first time, finish up by installing the dependencies and setting `.env` file values.

Install dependencies:

```sh
composer install
```

Copy the `.env` file:

```sh
cp .env.example .env
```

Generate an encryption key:

```sh
php artisan key:generate
```

Deploy with artisan serve:

```sh
php artisan serve
```

## Database
Execute the migration and seeders:
```sh
php artisan migrate:refresh --seed
```

## Testing
To run the tests, execute:

```sh
vendor/bin/phpunit --testdox
```

## Contributing
Contributions are always welcome, but before anything else, make sure you get acquainted with the [CONTRIBUTING](CONTRIBUTING.md) guide.

## License
This project is open source software licensed under the [MIT LICENSE](LICENSE.md).
