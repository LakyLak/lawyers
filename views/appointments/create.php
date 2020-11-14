<?php
$this->title = 'Create Appointment';

use app\core\helper\HTML;

/**
 * @var $lawyers array
 */

?>

<div class="container">
    <h1>Create Appointment</h1>
    <?php HTML::beginForm('', 'post') ?>
    <div class="col">
      <div class="form-group">
        <?php HTML::inputSelect('lawyerId', $lawyers, [],
                                ['label' => '', 'class' => 'custom-select', 'default' => 'Choose the lawyer']) ?>
      </div>
    </div>
    <div class="col">
      <div class="form-group">
        <?php HTML::submit('Next') ?>
      </div>
    </div>
    <?php HTML::endForm() ?>
</div>
