# LogBug Errors Notifier for Zend Framework 2
### LogBug?
The LogBug Notifier for Zend Framework 2 gives you instant notifications of the errors in your application.
Detect Handle and Catch E_* PHP errors , ‘dispatch.error’ and ‘render.error’ errors that handled by Framework
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
Create directory `data/logs` and make sure your application has write access to it.
Copy the `config/logbug.local.php.dist` file to your `config/autoload` folder and change the settings
```php
<?php

return [
    "logbug" => [
        "email" => [
            "smtp" => [
                'host' => 'smtp.server.com',
                'username' => 'error@server.com',
                'password' => 'yourpassword',
                'ssl' => 'tls',
                'port' => 587,
            ],
            'send' => [
                'from' => 'error@server.com',
                'subject' => 'Error notification',
                
                //e-mails to receive notification
                'to' => ['myteam@gmail.com', 'myteam2@mail.com']
            ],
            
            'ignore' => [
                //ignore errors
                'error' => [
                    'E_NOTICE',
//                    'EXCEPTION_RENDER_ERROR',
//                 'EXCEPTION_DISPATCH_ERROR'
                ],
                
                //ignore error with code
                'code'=>[
                    '403'
                ]
            ]
        ]
    ]
];
```
