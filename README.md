VarDumper Component with Context
================================

Using this will show the file and line number which called the dump function. Very handy if you've forgotten where you put that pesky call, especially deep inside the `vendor` directory.

## Cloned and updated from:

https://github.com/morrislaptop/var-dumper-with-context

Original package seems to be abandoned, so I decided to claim and maintain it. Credit to the original author/package is due though!

Installation
------------

    composer require symfony/var-dumper --dev && composer require morrislaptop/var-dumper-with-context --dev

Usage
-----

That's it! Simply call `dump()` as you normally would and the file and line number will appear. 

## Env Variables
If you would like to generate links to the file and line for IDE you can set the following environment variables:

```dotenv
VAR_DUMPER_REMOTE_BASE_PATH=/usr/share/deploys/your-project/current
VAR_DUMPER_LOCAL_BASE_PATH=/Users/your.user/dev/your-project
VAR_DUMPER_IDE=phpstorm
# vscode, phpstorm, sublime, textmate, emacs, macvim, idea, atom, nova, netbeans
```

Laravel Note
-----
Laravel 5.7 ships with `beyondcode/laravel-dump-server` which will disable this extension. To enable this extension again, simply add the below to your application's `composer.json` and run `php artisan package:discover` again.

```
    "extra": {
        "laravel": {
            "dont-discover": [
                "beyondcode/laravel-dump-server"
            ]
        }
    },
```

Resources
---------

* [Symfony VarDumper](https://symfony.com/doc/current/components/var_dumper/introduction.html)
