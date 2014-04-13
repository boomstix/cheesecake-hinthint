<?php

require_once('../assets/php/config.php');
require_once('../assets/php/lib.php');

$my_name = isset($_GET['name']) ? $_GET['name'] : 'Mum';
$my_name_err = '';

$my_email = isset($_GET['email']) ? $_GET['email'] : '';
$my_email_err = '';

$reminder_date = null;
$reminder_date_err = '';

$accept_terms = null;
$accept_terms_err = '';


$submitted = isset($_POST['submitted']);
$form_err = false;
$completed = false;

if ($submitted) :

	$my_name = isset($_POST['my_name']) ? substr($_POST['my_name'], 0 ,100) : false;
	$my_name_err = (strlen($my_name) < 2) ? 'Please state your name' : '';
	
	$my_email = isset($_POST['my_email']) ? substr($_POST['my_email'], 0, 100) : false;
	$my_email_err = !isValidEmail($my_email) ? 'Please state your email address' : '';
	
	$reminder_date = isset($_POST['reminder_date']) ? substr($_POST['reminder_date'], 0, 100) : false;
	$reminder_date_err = (strlen($reminder_date) == 0) ? 'Please select your reminder date' : '';
	
	$accept_terms = isset($_POST['accept_terms']);
	$accept_terms_err = $accept_terms ? '' : 'You must accept the terms and conditions';
	
	$form_err = $my_name_err || $my_email_err || $reminder_date_err  || $accept_terms_err;

	if (!$form_err) :
	
		// Has the recipient already been registered?
		$data = mysql_select_rows('SELECT id FROM reminder_data WHERE my_email = :my_email;', array(':my_email' => $my_email));
		// Assume its new
		$sql_str = "INSERT INTO `reminder_data` (`my_name`, `my_email`, `reminder_date`, `create_date`) VALUES (:my_name, :my_email, :reminder_date, now() );";
		if (count($data) > 0) :
			// We want to update it if its already been added
			$sql_str = "UPDATE `reminder_data` SET `my_name` = :my_name, `reminder_date` = :reminder_date, `create_date` = now() WHERE my_email = :my_email;";
		endif;
		// Execute the sql string
		mysql_execute($sql_str, array(':my_name' => $my_name, ':my_email' => $my_email, ':reminder_date' => $reminder_date));
		// Set the complete flag
		$completed = !$db_err;
	
	endif;
	
endif;

require_once(ROOT_DIR.HEADER_PATH);

if ($db_err):
	// render failure panel
	?>
<p>
There has been a problem with the database.
</p>
<?
else:

	if ($completed):
		// render thankyou panel
		?>

		<p>Thank you!</p>

<?
	else:
		// render input form
		?>

<div class="container">		
<h1>Hey, big momma!!</h1>
</div>

<!--		
<pre>
<? var_dump($_POST); ?>
</pre>
-->

<form name="landing_form" method="post">

<div class="container">

<fieldset class="form-group">

<div class="row">
	<div class="col-md-6">
		<fieldset class="form-group">
			<label for="my-name">Your name</label>
			<input type="text" class="form-control" id="my-name" name="my_name" value="<?= $my_name ?>" maxlength="100" />
		</fieldset>
	</div>
	<div class="col-md-6">
		<fieldset class="form-group">
			<label for="my-email">Your email</label>
			<input type="text" class="form-control" id="my-email" name="my_email" value="<?= $my_email ?>" maxlength="100" />
		</fieldset>
	</div>
</div>

<fieldset class="form-group">

	<p>I'd like a reminder set for:</p>
	<div class="radio">
		<label for="reminder-date-tuesday">Tuesday May 6</label>
		<input type="radio" id="reminder-date-tuesday" name="reminder_date" value="Tuesday May 6" <?= $reminder_date == 'Tuesday May 6' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="reminder-date-wednesday">Wednesday May 7</label>
		<input type="radio" id="reminder-date-wednesday" name="reminder_date" value="Wednesday May 6" <?= $reminder_date == 'Wednesday May 7' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="reminder-date-thursday">Thursday May 8</label>
		<input type="radio" id="reminder-date-thursday" name="reminder_date" value="Thursday May 8" <?= $reminder_date == 'Thursday May 8' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="reminder-date-friday">Friday May 9</label>
		<input type="radio" id="reminder-date-friday" name="reminder_date" value="Friday May 9" <?= $reminder_date == 'Friday May 9' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="reminder-date-saturday">Saturday May 10</label>
		<input type="radio" id="reminder-date-saturday" name="reminder_date" value="Saturday May 10" <?= $reminder_date == 'Saturday May 10' ? 'checked="checked" ' : '' ?>/>
	</div>
</fieldset>

<fieldset class="form-group">
	<input type="checkbox" id="accept-terms" name="accept_terms" <?= $accept_terms ? 'checked="checked" ' : '' ?>/>
	<label for="accept-terms">I agree to the terms and conditions</label>
</fieldset>

<fieldset class="form-group">
	<button id="btn-submitted" name="submitted" value="Send reminder">Send reminder</button>
</fieldset>

</div>

</form>

<?

		if ($form_err) : ?>

<div class="container">

<p>There has been a problem with your submission:</p>
<ul class="error">
	<?= $my_name_err ? '<li>'.$my_name_err.'</li>' : '' ?>
	<?= $my_email_err ? '<li>'.$my_email_err.'</li>' : '' ?>
	<?= $accept_terms_err ? '<li>'.$accept_terms_err.'</li>' : '' ?>
</ul>
</div>
	<?
	
		endif; // form_err

	endif; // completed

endif; // db_err

require_once(ROOT_DIR.SCRIPT_PATH);

require_once(ROOT_DIR.FOOTER_PATH);

?>