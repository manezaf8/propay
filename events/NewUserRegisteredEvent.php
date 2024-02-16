<?php

namespace Events;

class NewUserRegisteredEvent
{
    protected $userId;

    /**
     * User Register Event constructor
     *
     * @param integer $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
