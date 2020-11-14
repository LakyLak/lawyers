<?php

use app\controllers\HomeController;
use app\controllers\LawyerController;
use app\controllers\CitizenController;
use app\controllers\AppointmentController;

$routes = [
    ['get', '/', HomeController::class, 'actionHome'],

    ['get', '/lawyer/login', LawyerController::class, 'actionLogin'],
    ['post', '/lawyer/login', LawyerController::class, 'actionLogin'],
    ['get', '/lawyer/register', LawyerController::class, 'actionRegister'],
    ['post', '/lawyer/register', LawyerController::class, 'actionRegister'],
    ['get', '/lawyer/profile', LawyerController::class, 'actionProfile'],
    ['post', '/lawyer/profile', LawyerController::class, 'actionProfile'],
    ['get', '/logout', LawyerController::class, 'actionLogout'],

    ['get', '/citizen/login', CitizenController::class, 'actionLogin'],
    ['post', '/citizen/login', CitizenController::class, 'actionLogin'],
    ['get', '/citizen/register', CitizenController::class, 'actionRegister'],
    ['post', '/citizen/register', CitizenController::class, 'actionRegister'],
    ['get', '/citizen/profile', CitizenController::class, 'actionProfile'],
    ['post', '/citizen/profile', CitizenController::class, 'actionProfile'],
    ['get', '/logout', CitizenController::class, 'actionLogout'],

    ['get', '/appointments', AppointmentController::class, 'actionList'],
    ['get', '/appointment/add', AppointmentController::class, 'actionAdd'],
    ['post', '/appointment/add', AppointmentController::class, 'actionAdd'],
    ['get', '/appointment/edit', AppointmentController::class, 'actionEdit'],
    ['post', '/appointment/edit', AppointmentController::class, 'actionEdit'],
    ['get', '/appointment/delete', AppointmentController::class, 'actionDelete'],
];

