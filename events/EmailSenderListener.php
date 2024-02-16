<?php

namespace Events;

use League\Event\Listener;
use Controller\EmailSenderController;
use League\Event\EventInterface;

class EmailSenderListener implements Listener
{
    protected $emailSender;

    /**
     * Email sender event listner 
     *
     * @param EmailSenderController $emailSender
     */
    public function __construct(EmailSenderController $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    /**
     * invoke the event
     *
     * @param object $event
     * @return void
     */
    public function __invoke(object $event): void
    {
        // Access the new user ID from the event
        $userId = $event->getUserId();
        $this->emailSender->sendEmail($userId);
    }
}

