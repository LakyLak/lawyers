<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'webUserClasses' => [
        'Lawyer'  => \app\models\Lawyer::class,
        'Citizen' => \app\models\Citizen::class,
    ],
    'db'             => [
        'dsn'      => $_ENV['DB_DSN'],
        'user'     => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
];
