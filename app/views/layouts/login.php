<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login - HERO Timers</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Latest compiled and minified CSS -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?=URL::asset('css/bootstrap.css')?>">
	<link rel="stylesheet" href="<?=URL::asset('css/sticky.css')?>">
	<link rel="stylesheet" href="<?=URL::asset('css/util.css')?>">

	<!-- Latest compiled and minified JavaScript -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="<?=URL::asset('js/bootstrap.min.js')?>"></script>

	<style>
		.jumbotron {
			margin-top: 30px;
		}
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
