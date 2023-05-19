Symfony small project
========================
Requirements
------------

  * PHP 8.1.0 or higher;
  * PDO-MySQL PHP extension enabled;
  * and the [usual Symfony application requirements][1].

Installation
------------

1. [Download XAMPP][2] and start MySQL Database service.

2. [Download Composer][3] and use the `composer` binary installed
on your computer to run these commands:

```bash
$ git clone https://github.com/EugenD28/Small-Symfony-Project.git
$ cd Small-Symfony-Project/
$ composer install
```

3. Execute command for create DB 
```bash 
$ php bin/console doctrine:database:create
``` 
4. Rename file `.env.example` to `.env`
5. Execute command for create tables in database
```bash 
$ php bin/console doctrine:migration:migrate 
```
and input `no` in command line.
6. After is needed to execute command to create with random data some users, projects and milestones
```bash 
$ php bin/console doctrine:fixtures:load
```
and input `yes` in command line.
Usage
-----

[Download Symfony CLI][4] and run this command:

```bash
$ cd Small-Symfony-Project/
$ symfony server:start
```

Then access the application in your browser at the given URL (<http://localhost:8000> by default).

[1]: https://symfony.com/doc/current/setup.html#technical-requirements
[2]: https://www.apachefriends.org/
[3]: https://getcomposer.org/
[4]: https://symfony.com/download