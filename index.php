<?php

require_once('./assets/php/config.php');

$reminder_to = null;
$reminder_to_err = '';

$perfect_gift = null;
$perfect_gift_err = '';

$cake = null;
$cake_err = '';

$cake_other = null;
$cake_other_err = '';

$my_name = isset($_GET['name']) ? $_GET['name'] : 'Mum';
$my_name_err = '';

$my_email = isset($_GET['email']) ? $_GET['email'] : '';
$my_email_err = '';

$reminder_name = null;
$reminder_name_err = '';

$reminder_email = null;
$reminder_email_err = '';

$accept_terms = null;
$accept_terms_err = '';


$submitted = isset($_POST['submitted']);
$form_err = false;
$completed = false;

if ($submitted) :

	// echo "submitted";

	// get input
	$reminder_to = isset($_POST['reminder_to']) ? $_POST['reminder_to'] : false;
	$reminder_to_err = (strlen($reminder_to) < 2) ? 'Please choose to whom you want the reminder sent' : '';
	
	$perfect_gift = isset($_POST['perfect_gift']) ? $_POST['perfect_gift'] : false;
	$perfect_gift_err = (strlen($perfect_gift) == 0) ? 'Please choose your perfect gift' : '';
	
	$cake = isset($_POST['cake']) ? $_POST['cake'] : false;
	$cake_err = (strlen($cake) == 0) ? 'Please choose your favourite cake' : '';
	
	$cake_other = isset($_POST['cake_other']) ? $_POST['cake_other'] : false;
	$cake_other_err = (($cake == "other") && (strlen($cake_other) < 2)) ? 'Please state your preferred cake' : '';
	
	$my_name = isset($_POST['my_name']) ? $_POST['my_name'] : false;
	$my_name_err = (strlen($my_name) < 2) ? 'Please state your name' : '';
	
	$my_email = isset($_POST['my_email']) ? $_POST['my_email'] : false;
	$my_email_err = !isValidEmail($my_email) ? 'Please state your email address' : '';
	
	$reminder_name = isset($_POST['reminder_name']) ? $_POST['reminder_name'] : false;
	$reminder_err = (strlen($reminder_name) < 2) ? 'Please state the name of the person you want to remind' : '';
	
	$reminder_email = isset($_POST['reminder_email']) ? $_POST['reminder_email'] : false;
	$reminder_email_err = !isValidEmail($reminder_email) ? 'Please state the email address of the person you want to remind' : '';
	
	$accept_terms = isset($_POST['accept_terms']);
	$accept_terms_err = $accept_terms ? '' : 'You must accept the terms and conditions';
	
	$form_err = $reminder_to_err || $perfect_gift_err  || $cake_err || $cake_other_err || $my_name_err || $my_email_err || $reminder_name_err || $reminder_email_err || $accept_terms_err;

	if (!$form_err) :
	
		$completed = true;	
	
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

	<p>I'd like to send a reminder to:</p>

	<div class="radio">
		<label for="reminder-husband">My husband</label>
		<input type="radio" id="reminder-husband" name="reminder_to" value="My husband" <?= $reminder_to == 'My husband' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="reminder-daughter">My daughter</label>
		<input type="radio" id="reminder-daughter" name="reminder_to" value="My daughter" <?= $reminder_to == 'My daughter' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="reminder-son">My son</label>
		<input type="radio" id="reminder-son" name="reminder_to" value="My son" <?= $reminder_to == 'My son' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="reminder-other-family">Other family</label>
		<input type="radio" id="reminder-other-family" name="reminder_to" value="Other family" <?= $reminder_to == 'Other family' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="reminder-a-friend">A friend</label>
		<input type="radio" id="reminder-a-friend" name="reminder_to" value="A friend" <?= $reminder_to == 'A friend' ? 'checked="checked" ' : '' ?>/>
	</div>
</fieldset>

<fieldset class="form-group">
	<label for="perfect_gift">My perfect Mother's Day gift would be:</label>
	<select class="form-control" id="perfect-gift" name="perfect_gift">
		<option value="">Choose your perfect gift</option>
		<option<?= $perfect_gift == "Pretzels" ? ' selected="selected" ' : '' ?>>Pretzels</option>
		<option<?= $perfect_gift == "Onions" ? ' selected="selected" ' : '' ?>>Onions</option>
		<option<?= $perfect_gift == "Plastic bags" ? ' selected="selected" ' : '' ?>>Plastic bags</option>
		<option<?= $perfect_gift == "Extension cord" ? ' selected="selected" ' : '' ?>>Extension cord</option>
		<option<?= $perfect_gift == "Esky" ? ' selected="selected" ' : '' ?>>Esky</option>
	</select>
</fieldset>

<fieldset class="form-group">

	<p>But even better, a cake the whole family will enjoy:</p>
	<div class="radio">
		<label for="cake-black-forest">Black Forest</label>
		<input type="radio" id="cake-black-forest" name="cake" value="Black Forest" <?= $cake == 'Black Forest' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="cake-boston-mudcake">Boston Mudcake</label>
		<input type="radio" id="cake-boston-mudcake" name="cake" value="Boston Mudcake" <?= $cake == 'Boston Mudcake' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="cake-caramel-continental">Caramel Continental</label>
		<input type="radio" id="cake-caramel-continental" name="cake" value="Caramel Continental" <?= $cake == 'Caramel Continental' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="cake-marble-baked">Marble Baked</label>
		<input type="radio" id="cake-marble-baked" name="cake" value="Marble Baked" <?= $cake == 'Marble Baked' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="cake-dressed-pavlova">Dressed Pavlova</label>
		<input type="radio" id="cake-dressed-pavlova" name="cake" value="Dressed Pavlova" <?= $cake == 'Dressed Pavlova' ? 'checked="checked" ' : '' ?>/>
	</div>
	<div class="radio">
		<label for="cake-other">Other</label>
		<input type="radio" id="cake-other" name="cake" value="Other" <?= $cake == 'Other' ? 'checked="checked" ' : '' ?>/>
		<input type="text" class="btn-like" id="txt-cake-other" name="cake_other" value="<?= $cake_other ?>" />
	</div>
</fieldset>

<div class="row">
	<div class="col-md-6">
		<fieldset class="form-group">
			<label for="my-name">Your name</label>
			<input type="text" class="form-control" id="my-name" name="my_name" value="<?= $my_name ?>" />
		</fieldset>
	</div>
	<div class="col-md-6">
		<fieldset class="form-group">
			<label for="my-email">Your email</label>
			<input type="text" class="form-control" id="my-email" name="my_email" value="<?= $my_email ?>" />
		</fieldset>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<fieldset class="form-group">
			<label for="reminder-name" id="label-reminder-name">Reminder name</label>
			<input type="text" class="form-control" id="reminder-name" name="reminder_name" value="<?= $reminder_name ?>" />
		</fieldset>
	</div>
	<div class="col-md-6">
		<fieldset class="form-group">
			<label for="reminder-email" id="label-reminder-email">Reminder email</label>
			<input type="text" class="form-control" id="reminder-email" name="reminder_email" value="<?= $reminder_email ?>" />
		</fieldset>
	</div>
</div>

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
	<?= $reminder_to_err ? '<li>'.$reminder_to_err.'</li>' : '' ?>
	<?= $perfect_gift_err ? '<li>'.$perfect_gift_err.'</li>' : '' ?>
	<?= $cake_err ? '<li>'.$cake_err.'</li>' : '' ?>
	<?= $cake_other_err ? '<li>'.$cake_other_err.'</li>' : '' ?>
	<?= $my_name_err ? '<li>'.$my_name_err.'</li>' : '' ?>
	<?= $my_email_err ? '<li>'.$my_email_err.'</li>' : '' ?>
	<?= $reminder_name_err ? '<li>'.$reminder_name_err.'</li>' : '' ?>
	<?= $reminder_email_err ? '<li>'.$reminder_email_err.'</li>' : '' ?>
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