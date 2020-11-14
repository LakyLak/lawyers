<?php

use app\core\Application;
use app\core\db\DbModel;
use app\core\helper\HTML;

$webUser = Application::$app->webUser;

if ($webUser) {
  $class = strtolower((new \ReflectionClass($webUser))->getShortName());
  $primaryKey = DbModel::getPrimaryKeyName($webUser->tableName());
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/styles.css">
    <script src="/js/jquery-3.5.1.js"></script>

    <title><?php echo $this->title; ?></title>
    <link rel="shortcut icon" type="image/jpg" href="/images/favicon.ico"/>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color: #e3f2fd;">
      <a class="navbar-brand" href="/">Law Online Registry</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <?php if (isset($class) && $class == 'citizen'): ?>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Appointments
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="<?php HTML::url('/appointments', [$primaryKey => $webUser->{$primaryKey}]); ?>">List Appointments</a>
              <a class="dropdown-item" href="/appointment/create">Create Appointment</a>
            </div>
          </li>
          <?php elseif (isset($class) && $class == 'lawyer'): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php HTML::url('/appointments', [$primaryKey => $webUser->{$primaryKey}]); ?>">Appointments</a>
            </li>
          <?php endif; ?>
        </ul>
        <ul class="navbar-nav ml-auto">
          <?php if (Application::getCurrentUser()): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php HTML::url("/$class/profile", [$primaryKey => $webUser->{$primaryKey}]); ?>">Profile</a>
          </li>
            <li class="nav-item">
            <a class="nav-link" href="/logout">Welcome <?php echo $webUser->getDisplayName() ?> (Logout)</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>

    <?php if (Application::$app->session->getFlashMessage('success') ): ?>
      <div class="alert alert-success"><?php echo Application::$app->session->getFlashMessage('success'); ?></div>
    <?php endif; ?>
<!--    <div class="container">-->
      {{content}}
<!--    </div>-->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  </body>
</html>
