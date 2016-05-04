Pimcore Doctrine Migrations
===========================

In some Pimcore workflows you may find yourself repeatedly setting up the same content on several
different environments (e.g. locally -> dev -> uat -> live). Doing this work manually is not only
cumbersome, but it's also incredibly error prone. To alleviate this issue Byng decided to use
Doctrine Migrations.

Using Doctrine Migrations you can automate database changes, and changes within Pimcore itself. For
example; create pages, object, changing settings, etc.

## Installation

Require the library via Composer (this library must be installed via Composer):

```
$ composer require byng/pimcore-doctrine-migrations-library
```

Then in your project's `composer.json` file add the following snippets:

```
{
    ...
    "config": {
        "project-root-path": "./"
    },
    ...
    "scripts": {
        "post-install-cmd": [
            "Byng\\Pimcore\\DoctrineMigrations\\Installer::install"
        ],
        "post-update-cmd": [
            "Byng\\Pimcore\\DoctrineMigrations\\Installer::install"
        ]
    }
    ...
}
```

The project root path is necessary so that the installer can create a migrations folder.

Note: The installation script will overwrite the migrations configuration files created in the
project root. Changes to these files will be overwritten on composer install and update. We
recommend placing them in your ignore file(s).

## Usage

Once installed, you can simply use the library like you normally would with Doctrine Migrations. See
[their documentation here][1].

## License

MIT


[1]: http://docs.doctrine-project.org/projects/doctrine-migrations/en/latest/
