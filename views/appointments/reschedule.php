<?php

/**
 * @var $appointment Appointment
 * @var $bookings array
 */

use app\models\Calendar;

?>

<div class="container">
    <h3>Reschedule the appointment</h3>
</div>

<?php return Calendar::renderWeekCalendar($bookings, 0, time(), $appointment->lawyerId, true, $appointment->appointmentId); ?>
