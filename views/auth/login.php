<?php
use app\core\helper\HTML;

$this->title = 'Login';

/** @var $model LoginForm */

$class = strtolower((new \ReflectionClass($model->webUser))->getShortName());
?>
<div class="form-card form-card-<?php echo $class ?>">
  <h1>Login</h1>

  <?php HTML::beginForm('', 'post') ?>
    <div class="form-group">
      <?php HTML::inputText($model, 'email') ?>
    </div>
    <div class="form-group">
      <?php HTML::inputPassword($model, 'password') ?>
    </div>
    <?php HTML::submit() ?>
  <?php HTML::endForm() ?>
</div>

