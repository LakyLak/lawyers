<?php
$this->title = 'Profile';

use app\core\helper\HTML;

/** @var $model Lawyer|Citizen */

?>

<div class="container">
    <h1>Person</h1>
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
    <div class="row">
      <div class="col">
        <div class="form-group">
          <?php HTML::inputEmail($model, 'email', ['html' => ['disabled']]) ?>
        </div>
      </div>
    </div>
    <?php HTML::inputHidden($model, 'lawyerId', ['label' => '']) ?>
    <?php HTML::submit('Update') ?>
    <?php HTML::endForm() ?>
</div>
