<style>
	.form-signin {
		padding: 15px;
		margin: 0 auto;
	}
	.form-signin .form-signin-heading,
	.form-signin .checkbox {
		margin-bottom: 10px;
		margin-top: 0;
	}
	.form-signin .checkbox {
		font-weight: normal;
	}
	.form-signin input[type="text"],
	.form-signin input[type="password"] {
		position: relative;
		font-size: 16px;
		height: auto;
		padding: 10px;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.form-signin input[type="text"]:focus,
	.form-signin input[type="password"]:focus {
		z-index: 2;
	}
	.form-signin input[type="text"] {
		margin-bottom: -1px;
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}
	.form-signin input[type="password"] {
		margin-bottom: 10px;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
	}
</style>


<form action="" method="POST" class="form-signin well">
	<h2 class="form-signin-heading">Sign In</h2>

	<!-- check for login error flash var -->
	<?php
	if (Session::has('flash_error'))
	{
		?>
		<div id="flash_error" class="alert alert-danger"><?=Session::get('flash_error')?></div>
	<?php
	}
	?>


	<button class="btn btn-primary btn-lg btn-block" type="submit">Click Here to Authorize</button>
</form>