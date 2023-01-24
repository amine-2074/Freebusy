<h1 align="center">
  FreeBusy :rocket:
</h1>

Link: https://github.com/amine-2074/Freebusy
> **FreeBusy**: Adjective

   * [Getting Started](#Getting-Started)
   * [Dependencies](#Dependencies)
   * [Installation](#installation)
   * [Local Server](#Local-Server)
   * [Created using](#created)
   * [Migrations](#migrations)
   * [Validations](#validations)
   * [Requirements](#requirements)


## Getting Started

Ces instructions vous permettent d'avoir une copie du projet fonctionnelle sur votre machine locale pour pouvoir développer et tester le projet.

### Dependencies
The project is based on the PHP Laravel 5.8 framework and needs the following modules to work:
* PHP >= 8.0.2
* guzzle >= 7.2
* fMbstringramework >= 9.19
* sanctum >= 3.0
* tinker >= 2.7
* composer = 2.5.1

### Installation

The first step is to create a database to allow the application to connect to it by replacing the .env file with the contents of .env.example file

Then just import the data and tables and create an encryption key using the following commands :

```
php artisan migrate     //migrate the tables to the database 
php artisan db:seed     //sends the data of the file freebusy.txt to the database after the treatment
php artisan key:generate    //generates a key for the application
```

### Local-Server

This command launches a local development server at the address [http://localhost:8000](http://localhost:8000)
```
php artisan serve
```

## created
* [Laravel](https://laravel.com/docs/9.x)


### Migrations
The name of the migrations follows an ```action_tool_table``` type naming logic with the action being one of the following values:

- Create for the creation of new table

```shell
php artisan make:migration create_project_files_table --create=project_files
```
- Add for adding columns to existing tables
```shell
php artisan make:migration add_votes_to_project_files_table --table=project_files
```
- Change for modifying columns of existing tables
```shell
php artisan make:migration change_clientid_project_files_table --table=project_files
```
The set of methods for creating and modifying tables is available in the [Laravel 5.8 documentation](https://laravel.com/docs/9.x/migrations)

### Requirements
In the project folder you will find a folder named requirements inside the folder there is 3 php.ini files that I invite you to copy them and past them in the place of the old ones inside your php folder of xampp.

### tests
To run tests all what you have to do is to run the following command

```shell
composer test
```

after runing the command there is a folder named coverage that will be automatically generated, if you want to see the result of tests I invite you to open the file index.html inside the coverage folder.