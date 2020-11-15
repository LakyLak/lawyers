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
    ['get', '/appointment/create', AppointmentController::class, 'actionCreate'],
    ['post', '/appointment/create', AppointmentController::class, 'actionCreate'],
    ['get', '/appointment/buildCalendar', AppointmentController::class, 'actionBuildCalendar'],
    ['get', '/appointment/cancel', AppointmentController::class, 'actionCancel'],
    ['get', '/appointment/schedule', AppointmentController::class, 'actionSchedule'],
    ['post', '/appointment/schedule', AppointmentController::class, 'actionSchedule'],
];

