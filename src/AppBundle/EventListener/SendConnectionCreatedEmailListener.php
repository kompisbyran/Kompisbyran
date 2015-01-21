<?php

namespace AppBundle\EventListener;

use AppBundle\Event\ConnectionCreatedEvent;
use AppBundle\Service\EmailSender;

class SendConnectionCreatedEmailListener
{
    /**
     * @var \AppBundle\Service\EmailSender
     */
    protected $emailSender;

    /**
     * @param EmailSender $emailSender
     */
    public function __construct(EmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    /**
     * @param ConnectionCreatedEvent $event
     */
    public function whenConnectionCreated(ConnectionCreatedEvent $event)
    {
        $this->emailSender->sendConnectionCreatedEmail($event->getConnection());
    }
}
