<?php
namespace LogBug;

use Zend\Mvc\MvcEvent;
use LogBug\Service\Error as LogBugError;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
       $logbug= new LogBugError();
        
        $application        =   $e->getTarget();
        $eventManager       =   $application->getEventManager();
        $services           =   $application->getServiceManager();


        //$service            =   $services->get('BugsnagServiceException');
        // Register the PHP exception and error handlers
        //$service->setupErrorHandlers();
 

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function ($event) use ($services) {
           $exception      =   $event->getParam('exception');
            echo 'erro';
            exit();
            // No exception, stop the script
            if (!$exception) return;

            
            echo 'erro';
            exit();
           // $service->sendException($exception);
        });
    }
    


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'BugsnagServiceException' =>  function($sm) {
                    $options = $sm->get('ZfBugsnag\Options\BugsnagOptions');
                    $service = new \ZfBugsnag\Service\BugsnagService($options);
                    return $service;
                },
            ],
        ];
    }
}
