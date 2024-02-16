<?php

/**
 * @package   Sender event listner
 * @author    Ntabethemba Ntshoza
 * @date      16-02-2024
 * @copyright Copyright © 2024 VMP By Maneza
 */

namespace Events;

use Controller\EmailSenderController;
use League\Event\Listener;

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
