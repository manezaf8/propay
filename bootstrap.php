<?php

require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/helper/functions.php';

$config = include 'config.php';
$extensionPath = $config['path']['additionalPath'];

define('BASE_URL', getSiteUrl());
define('EXTENTION_PATH', $extensionPath);

use Controller\EmailSenderController;
use Events\EmailSenderListener;
use Events\NewUserRegisteredEvent;
use League\Event\EventDispatcher;
use League\Event\ListenerPriority;
use League\Event\PrioritizedListenerRegistry;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

// Create the logger
$logger = new Logger('PROPAY-DEBUG');
// Now add some handlers
$logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));

// Create an instance of the listener registry
$listenerRegistry = new PrioritizedListenerRegistry();

$dispatcher = new EventDispatcher($listenerRegistry);

// Create an instance of the listener and pass EmailSenderController
$listener = new EmailSenderListener(new EmailSenderController());

// Subscribe to the event with the listener and priority
$dispatcher->subscribeTo(NewUserRegisteredEvent::class, $listener, ListenerPriority::NORMAL);
