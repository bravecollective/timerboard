<div class="page-header" style="margin: 0 0 20px;">
	<h2>
		<a href="<?=URL::route('home')?>" class="pull-right btn btn-default">Back to List</a>
		Add Timer
	</h2>
</div>

<?php
if (Session::has('flash_error'))
{
	?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div id="flash_error" class="alert alert-danger"><?=Session::get('flash_error')?></div>
		</div>
	</div>
	<?php
}
?>

<?=Form::open(array('route' => array('add_timer')))?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

			<div class="form-group">
				<?=Form::label('itemID', 'Celestial Name')?>
				<?=Form::text('itemID', '', array('id' => 'itemID'))?>
			</div>

			<div class="form-group">
				<?=Form::label('timeExiting', 'Time Left')?>

				<div class="input-group" style="margin-bottom: 10px;">
					<span class="input-group-addon">Days:</span>
					<?=Form::selectRange('days', 0, 2, null, array('class' => 'form-control'))?>
				</div>

				<div class="input-group" style="margin-bottom: 10px;">
					<span class="input-group-addon">Hours:</span>
					<?=Form::selectRange('hours', 0, 24, null, array('class' => 'form-control'))?>
				</div>

				<div class="input-group" style="margin-bottom: 10px;">
					<span class="input-group-addon">Minutes:</span>
					<?=Form::selectRange('mins', 0, 60, null, array('class' => 'form-control'))?>
				</div>
			</div>

			<div class="form-group">
				<?=Form::label('timerType', 'Timer Type')?>
				<?=Form::select('timerType', Timers::$timerType, '', array('id' => 'timerType', 'class' => 'form-control'))?>
			</div>

			<div class="form-group">
				<?=Form::label('structureType', 'Structure Type')?>
				<?=Form::select('structureType', Timers::$structureTypes, '', array('id' => 'structureType', 'class' => 'form-control'))?>
			</div>

			<?php
			$timer_status = Timers::$structureStatus;
			unset($timer_status[0]);
			?>
			<div class="form-group">
				<?=Form::label('structureStatus', 'Structure Status')?>
				<?=Form::select('structureStatus', $timer_status, '', array('id' => 'structureStatus', 'class' => 'form-control'))?>
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-primary">Add Timer</button>
			</div>

		</div>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function()
	{
		//$('#timeExiting').datetimepicker();

		$("#itemID").select2({
			placeholder: "Search for Celestials",
			minimumInputLength: 3,
			ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
				url: "<?=URL::route('search_map')?>",
				dataType: 'jsonp',
				data: function (term, page) {
					return {
						q: term, // search term
						page_limit: 10
					};
				},
				results: function (data, page) { // parse the results into the format expected by Select2.
					// since we are using custom formatting functions we do not need to alter remote JSON data
					return {results: data.locations};
				}
			},
			formatResult: function(location)
			{
				return "<span>"+location.text+"</span>";
			}, // omitted for brevity, see the source of this page
			formatSelection: function(location)
			{
				return location.text;
			},  // omitted for brevity, see the source of this page
			dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
			escapeMarkup: function (m)
			{
				return m;
			} // we do not want to escape markup since we are displaying html in results
		});
	});
</script>