<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HERO Timers</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?=URL::asset('css/bootstrap.css')?>">
	<link rel="stylesheet" href="<?=URL::asset('css/sticky.css')?>">
	<link rel="stylesheet" href="<?=URL::asset('css/util.css')?>">
	<link rel="stylesheet" href="<?=URL::asset('css/select2.css')?>">
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/alertify.js/0.3.10/alertify.core.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/alertify.js/0.3.10/alertify.default.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script src="<?=URL::asset('js/bootstrap.min.js')?>"></script>
	<script src="<?=URL::asset('js/jquery.moment.js');?>"></script>
	<script src="<?=URL::asset('js/livestamp.js');?>"></script>
	<script src="<?=URL::asset('js/select2.min.js')?>"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/alertify.js/0.3.10/alertify.min.js"></script>

    <style>
        .jumbotron {
	        margin-top: 30px;
        }

        .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
        .ui-timepicker-div dl { text-align: left; }
        .ui-timepicker-div dl dt { float: left; clear:left; padding: 0 0 0 5px; }
        .ui-timepicker-div dl dd { margin: 0 10px 10px 40%; }
        .ui-timepicker-div td { font-size: 90%; }
        .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }

        .ui-timepicker-rtl{ direction: rtl; }
        .ui-timepicker-rtl dl { text-align: right; padding: 0 5px 0 0; }
        .ui-timepicker-rtl dl dt{ float: right; clear: right; }
        .ui-timepicker-rtl dl dd { margin: 0 40% 10px 10px; }
    </style>
</head>
<body>
	<!-- Wrap all page content here -->
	<div id="wrap">
		<?php
		/*
		 * Load the page content here
		 */
		echo $navigation;
		?>

		<div class="container">
			<?php
			/*
			 * Load the page content here
			 */
			echo $page_content;
			?>
		</div> <!-- /container -->
	</div>

	<?php
	/*
	 * Load the page content here
	 */
	echo $footer;
	?>
</body>
</html>
