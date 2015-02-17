<div id="footer">
	<div class="container">
		<p class="text-muted credit">
			<!--<span class="pull-right">Help</span>-->
			© HERO - 2013
		</p>
	</div>
</div>
<script>
	$(document).ready(function()
	{
		$('[data-toggle=tooltip]').tooltip();
		
		$('.moment').each(function(){
			var mr = moment($(this).data('moment'));
			$(this).html(mr.calendar());
		});
		
		$('.deleteButton').on('click', function(e)
		{
			e.preventDefault();
			var link = $(this).attr('href');

			// confirm dialog
			alertify.confirm("Are you sure you want to delete this?", function (ee)
			{
				if (ee)
				{
					// user clicked confirm
					window.location = link;
				}
				else
				{
					// user clicked "cancel", do nothing
				}
			});

			return false;
		});

		setInterval(function()
		{
			$('.time-now').html(moment().format("dddd, MMMM Do YYYY, h:mm:ss a"));
		}, 1000);
	});
</script>
