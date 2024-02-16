<?php
$config = include 'config.php';
$extensionPath = $config['path']['additionalPath'];

return [
    "{$extensionPath}/" => "HomeController::index",

    "{$extensionPath}/user-submit" => "AddPersonController::submit",

    "{$extensionPath}/user-edit" => "UserEditController::edit",
    "{$extensionPath}/user-edit-submit" => "UserEditSubmitController::submit",
    "{$extensionPath}/user-delete" => "UserDeleteController::delete",
    "{$extensionPath}/user-display" => "ProfileController::profile",
];
