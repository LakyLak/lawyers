<?php
use app\core\helper\HTML;

/** @var $model Lawyer|Citizen */

$class = strtolower((new \ReflectionClass($model))->getShortName());
?>

<div class="form-card form-card-<?php echo $class ?>">
  <h1>Register</h1>

  <br>
  <?php HTML::beginForm('', 'post') ?>
    <div class="row">
        <div class="col">
          <div class="form-group">
            <?php HTML::inputText($model, 'firstName') ?>
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <?php HTML::inputText($model, 'lastName') ?>
          </div>
        </div>
    </div>
    <div class="form-group">
        <?php HTML::inputText($model, 'email') ?>
    </div>
    <div class="form-group">
        <?php HTML::inputPassword($model, 'password') ?>
    </div>
    <div class="form-group">
        <?php HTML::inputPassword($model, 'confirmPassword') ?>
    </div>
    <div class="form-group">
        <?php HTML::submit() ?>
    </div>

  <?php HTML::endForm() ?>

</div>
