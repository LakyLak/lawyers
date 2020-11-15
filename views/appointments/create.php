<?php
$this->title = 'Create Appointment';

use app\core\helper\HTML;

/**
 * @var $lawyers array
 * @var $bookings array
 * @var $direction int
 * @var $timestamp int
 */

?>

<div class="container">
    <h1>Create Appointment</h1>
    <div class="row">
      <div class="col-4">
        <div class="form-group">
          <?php HTML::inputSelect(
              'lawyer', $lawyers, [],
              ['label' => '', 'class' => 'custom-select', 'default' => 'Choose the lawyer']
          ) ?>
        </div>
      </div>
    </div>
</div>

<div id="cal-container" style="display: none;"></div>

<script type="text/javascript">
  $(document).ready(function ()
	{
		$('#lawyer').change(function ()
		{
		  var lawyer = parseInt($(this).val());

      jQuery.ajax({
        url: "/appointment/buildCalendar?lawyerId=" + lawyer,
        type: "GET",
        success: function(data) {
          $("#cal-container").html(data).show();
        },
        error: function(data) {
          if (data.status == 500)
          {
            $("#cal-container").html(data.responseText);
          }
        }
      });
		});
	});
</script>
