<?php
use Carbon\Carbon;

if (Session::has('flash_error'))
{
	?>
	<div id="flash_error" class="alert alert-danger"><?=Session::get('flash_error')?></div>
	<?php
}

if (Session::has('flash_msg'))
{
	?>
	<div id="flash_error" class="alert alert-info"><?=Session::get('flash_msg')?></div>
	<?php
}
?>

<div class="row">
	<div class="col-lg-12">
		<h3>
			Expired Timers
		</h3>
		<div>
			<?php
			/*
			 * Load the timer content here
			 */
			echo $timer_table;
			?>
		</div>
	</div>
</div>