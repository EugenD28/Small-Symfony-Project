Symfony small project
========================
Requirements
------------

  * PHP 8.1.0 or higher;
  * PDO-MySQL PHP extension enabled;
  * and the [usual Symfony application requirements][1].

Installation
------------

[Download XAMPP][2] and start MySQL Database service.

[Download Composer][3] and use the `composer` binary installed
on your computer to run these commands:

```bash
$ git clone https://github.com/EugenD28/Small-Symfony-Project.git
$ cd Small-Symfony-Project/
$ composer install
```

Create database with name `symfony-task` and rename file `.env.example` to `.env`

Usage
-----

[Download Symfony CLI][4] and run this command:

```bash
$ cd Small-Symfony-Project/
$ symfony server:start
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

[1]: https://symfony.com/doc/current/setup.html#technical-requirements
[2]: https://www.apachefriends.org/
[3]: https://getcomposer.org/
[4]: https://symfony.com/download