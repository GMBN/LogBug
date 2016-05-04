<?php

namespace LogBug;

use Zend\Mvc\MvcEvent;
use LogBug\Service\Error as LogBugError;

class Module {

    public function onBootstrap(MvcEvent $e) {

        $application = $e->getTarget();
        $eventManager = $application->getEventManager();
        $sm = $application->getServiceManager();

        $logbug = $sm->get('LogBugServiceError');


        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function ($event) use ($sm) {
            $exception = $event->getParam('exception');
            if (!$exception) {
                return;
            }

            $config = $sm->get('Config');
            $ignoreCode = $config['logbug']['email']['ignore']['code'];

            if (in_array($exception->getCode(), $ignoreCode)) {
                return;
            }

            $mail = $sm->get('LogBug\Service\Email');
            $mail->send($exception, 'EXCEPTION_DISPATCH_ERROR');
        });

        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, function ($event) use ($sm) {
            $exception = $event->getParam('exception');
            if (!$exception) {
                return;
            }

            $config = $sm->get('Config');
            $ignoreCode = $config['logbug']['email']['ignore']['code'];

            if (in_array($exception->getCode(), $ignoreCode)) {
                return;
            }

            $mail = $sm->get('LogBug\Service\Email');
            $mail->send($exception, 'EXCEPTION_RENDER_ERROR');
        });
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getServiceConfig() {
        return [
            'factories' => [
                'LogBugServiceError' => function($sm) {
                    $mail = $sm->get('LogBug\Service\Email');
                    $service = new \LogBug\Service\Error($mail);
                    return $service;
                },
            ],
        ];
    }

}
