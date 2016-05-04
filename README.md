# LogBug Errors Notifier for Zend Framework 2
### LogBug?
The LogBug Notifier for Zend Framework 2 gives you instant notifications of the errors in your application.
### Install
#### Installation with the composer
```sh
php composer.phar require gmbn/logbug:dev-master
```

Enable it in your `application.config.php` file
```php
<?php
return array(
    'modules' => array(
        'LogBug', // Must be added as the first module
        // ...
    ),
    // ...
);
```
### Configuration

Copy the `config/logbug.local.php.dist` file to your `config/autoload` folder and change the settings
