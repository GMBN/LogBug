<?php

namespace LogBug\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Email implements ServiceLocatorAwareInterface {

    use ServiceLocatorAwareTrait;

    public function send($error, $typestr) {
        $configEmail = $this->getConfigEmail();
        $ignore = $configEmail['ignore']['error'];
        if (in_array($typestr, $ignore)) {
            return;
        }
        $message = $this->message($error, $typestr);

        $smtpOptions = $this->smtpOptions();

        $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
        $transport->send($message);

        $this->logWrite($error);
    }

    public function message($error, $typestr) {
        $currentDate = new \DateTime('now');
        $configEmail = $this->getConfigEmail();
        $configSend = $configEmail['send'];
        $from = $configSend['from'];
        $subject = $configSend['subject'];
        $to = $configSend['to'];

        $message = new \Zend\Mail\Message();
        $message->setFrom($from);
        foreach ($to as $emailTo) {
            $message->addTo($emailTo);
        }
        $message->setSubject($subject . ' - ' . $typestr);
        $errorMessage = "Host => " . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "\n"
                . "IP Request => " . $_SERVER['REMOTE_ADDR'] . "\n"
                . "Date => " . $currentDate->format('Y-m-d H:i') . "\n " 
                . $error;
        $message->setBody($errorMessage);
        $message->setEncoding("UTF-8");
        return $message;
    }

    public function smtpOptions() {
        $configEmail = $this->getConfigEmail();
        $configSmtp = $configEmail['smtp'];

        $host = $configSmtp['host'];
        $username = $configSmtp['username'];
        $password = $configSmtp['password'];
        $port = $configSmtp['port'];
        $ssl = $configSmtp['ssl'];

        $smtpOptions = new \Zend\Mail\Transport\SmtpOptions();
        $smtpOptions->setHost($host)
                ->setName($host)
                ->setConnectionClass('login')
                ->setPort($port)
                ->setConnectionConfig(array(
                    'username' => $username,
                    'password' => $password,
                    'ssl' => $ssl,
        ));

        return $smtpOptions;
    }

    private function getConfigEmail() {
        $sm = $this->getServiceLocator();
        $config = $sm->get('Config');
        $configEmail = $config['logbug']['email'];
        return $configEmail;
    }

    private function logWrite($message) {
        //logging...
        $logger = new \Zend\Log\Logger;
//stream writer         
        $writerStream = new \Zend\Log\Writer\Stream($_SERVER['DOCUMENT_ROOT'] . '/../data/logs/' . date('Ymd') . '-log.txt');
        $logger->addWriter($writerStream);

//log it!
        $delimiter = "\n ____________ \n";
        $logger->crit($message . $delimiter);
    }

}
