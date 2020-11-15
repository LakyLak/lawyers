<?php

use app\core\helper\HTML;
use app\models\Appointment;
use app\models\Calendar;

/**
 * @var $lawyerId int
 * @var $bookings array
 * @var $direction int
 * @var $timestamp int
 * @var $reschedule bool
 * @var $appointmentId int
 */

$cal = new Calendar();
$week = $cal->getWeekInfo($timestamp);

?>

<div class="container" id="week-calendar">
  <div class="cal-navigation">
  <a id="prev" class="cal-nav btn btn-secondary">Previous Week</a>
  <a id="today" class="cal-nav btn btn-info">Today</a>
  <a id="next" class="cal-nav btn btn-secondary">Next Week</a>
  </div>
  <br>
  <table class="table table-bordered cal cal-week">
    <thead class="thead-dark">
      <tr>
        <?php foreach($week['week'] as $dayNo => $weekDay): ?>
            <th><?php echo Calendar::$daysOfWeek[$dayNo] ?></th>
        <?php endforeach; ?>
      </tr>
      <tr>
        <?php foreach($week['week'] as $dayDate): ?>
          <?php fwrite($handle, 'dayDate => ' . $dayDate . PHP_EOL); ?>
          <td><?php echo $dayDate ?></td>
        <?php endforeach; ?>
      </tr>
    </thead>

    <tbody>
    <?php for($hour = 9; $hour <= 17; $hour++): ?>
        <tr>
      <?php foreach($week['week'] as $dayNo => $dayDate): ?>
          <td class="<?php
            echo (date('Y-m-d') == $dayDate) ? 'today' : '';
          ?>">
          <?php
            $btnClass = 'success';
            foreach ($bookings as $date => $hours) {
              if ($dayDate == $date) {
                foreach ($hours as $bookingHour) {
                  if ($bookingHour == $hour) {
                      $btnClass = 'danger';
                  }
                }
              }
            }
            if (date('Y-m-d') > $dayDate || (date('Y-m-d') == $dayDate && date('H') > $hour)) {
              $btnClass .= ' disabled';
            }
          ?>
          <?php
            HTML::beginForm($reschedule ? '/appointment/schedule' : '/appointment/create', 'post');
            HTML::inputHidden(new Appointment(), 'lawyerId', ['customValue' => $lawyerId]);
            HTML::inputHidden(new Appointment(), 'date', ['customValue' => $dayDate]);
            HTML::inputHidden(new Appointment(), 'hour', ['customValue' => $hour]);
            if ($reschedule) {
                HTML::inputHidden(new Appointment(), 'appointmentId', ['customValue' => $appointmentId]);
            }
            HTML::submit(Appointment::getHumanHour($hour)['start'], ['class' => "btn btn-$btnClass"]);
            HTML::endForm();
          ?>
        </td>
      <?php endforeach; ?>
    </tr>
    <?php endfor; ?>
    </tbody>
  </table>
</div>

<script type="text/javascript">
  $(document).ready(function ()
  {
  	$('.cal-navigation a').click(function(event) {
		  event.preventDefault();
      var appointmentId = '<?php echo ($appointmentId ?? '') ?>';
      var suffix = '';
		  if (appointmentId != '') {
		  	suffix = "&appointmentId=" + appointmentId;
      }

		  if ($(this).attr('id') == 'today')
      {
        var params = "lawyerId=<?php echo $lawyerId; ?>&direction=0" + suffix;
      } else {
		    var params = $(this).attr('id') + "=true&" +
            jQuery.param({lawyerId:<?php echo $lawyerId; ?>,  direction:<?php echo $direction; ?>}) + suffix;
      }

      jQuery.ajax({
        url: "/appointment/buildCalendar?" + params,
        type: "GET",
        success: function(data) {
        	console.log(data);

          $("#week-calendar").html(data);
        },
        error: function(data) {
          if (data.status == 500)
          {
            $("#week-calendar").html(data.responseText);
          }
        }
		  });
    });
  });
</script>

<script src="/js/jquery-3.5.1.js"></script>

