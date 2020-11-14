<?php

use app\core\Application;
use app\core\helper\HTML;
use app\models\Appointment;

$this->title = 'Appointments';

$handle = fopen($_SERVER['DOCUMENT_ROOT'] .'/logs/log.txt','a+');

fwrite($handle, '$data' . PHP_EOL);
fwrite($handle, print_r($data, true) . PHP_EOL);

?>

<div class="container">
    <h3>Appointments</h3>
    <table class="table table-striped">
      <thead class="thead-dark">
        <tr>
          <th><?php echo Application::getClassName() == 'citizen' ? 'Lawyer' : 'Citizen' ?></th>
          <th>Appointment time</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($data as $row): ?>
        <tr>
          <td><?php echo Application::getClassName() == 'citizen'
                  ? $row->lawyer->getDisplayName() : $row->citizen->getDisplayName() ?></td>
          <td><?php echo $row->getDisplayDateTime() ?></td>
          <td><?php echo Appointment::$statuses[$row->status] ?></td>
          <td>
            <?php
              if(Application::getClassName() == 'lawyer' && $row->status == Appointment::STATUS_NEW) {
                  HTML::actionButton('approve', ['appointment', 'appointmentId=' . $row->appointmentId]);
                  HTML::actionButton('cancel',
                                           ['appointment', 'appointmentId=' . $row->appointmentId],
                                           ['title' => 'Reject'],
                    );
              } elseif(Application::getClassName() == 'citizen') {
                  if ($row->status != Appointment::STATUS_APPROVED) {
                      HTML::actionButton('schedule',
                                             ['appointment', 'appointmentId=' . $row->appointmentId],
                                             ['title' => 'Reschedule']
                      );
                  }
                  if($row->status == Appointment::STATUS_NEW) {
                      HTML::actionButton('cancel', ['appointment', 'appointmentId=' . $row->appointmentId],
                                               ['onclick' => ' onclick="return confirm(\'Are you sure you want to cancel this appointment?\');"']);
                  }
              }
            ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
</div>

