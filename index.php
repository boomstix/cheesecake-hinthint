<?php

require_once('./assets/php/config.php');
require_once('./assets/php/lib.php');

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
	$reminder_to = isset($_POST['reminder_to']) ? substr($_POST['reminder_to'], 0, 100) : false;
	$reminder_to_err = (strlen($reminder_to) < 2) ? 'Please choose to whom you want the reminder sent' : '';
	
	$perfect_gift = isset($_POST['perfect_gift']) ? substr($_POST['perfect_gift'], 0, 100) : false;
	$perfect_gift_err = (strlen($perfect_gift) == 0) ? 'Please choose your perfect gift' : '';
	
	$cake = isset($_POST['cake']) ? substr($_POST['cake'], 0, 100) : false;
	$cake_err = (strlen($cake) == 0) ? 'Please choose your favourite cake' : '';
	
	if ($cake == "Other"):
		$cake_other = isset($_POST['cake_other']) ? substr($_POST['cake_other'], 0, 100) : false;
		$cake_other_err = (strlen($cake_other) < 2) ? 'Please state your preferred cake' : '';
	endif;
	
	$my_name = isset($_POST['my_name']) ? substr($_POST['my_name'], 0, 100) : false;
	$my_name_err = (strlen($my_name) < 2) ? 'Please state your name' : '';
	
	$my_email = isset($_POST['my_email']) ? substr($_POST['my_email'], 0, 100) : false;
	$my_email_err = !isValidEmail($my_email) ? 'Please state your email address' : '';
	
	$reminder_name = isset($_POST['reminder_name']) ? substr($_POST['reminder_name'], 0, 100) : false;
	$reminder_err = (strlen($reminder_name) < 2) ? 'Please state the name of the person you want to remind' : '';
	
	$reminder_email = isset($_POST['reminder_email']) ? substr($_POST['reminder_email'], 0, 100) : false;
	$reminder_email_err = !isValidEmail($reminder_email) ? 'Please state the email address of the person you want to remind' : '';
	
	$accept_terms = isset($_POST['accept_terms']);
	$accept_terms_err = $accept_terms ? '' : 'You must accept the terms and conditions';
	
	$form_err = $reminder_to_err || $perfect_gift_err  || $cake_err || $cake_other_err || $my_name_err || $my_email_err || $reminder_name_err || $reminder_email_err || $accept_terms_err;

	if (!$form_err) :
	
		// Has the recipient already been registered?
		$data = mysql_select_rows('SELECT id FROM landing_data WHERE reminder_email = :reminder_email;', array(':reminder_email' => $reminder_email));
		// Assume its new
		$sql_str = "INSERT INTO `landing_data` (`reminder_to`, `perfect_gift`, `cake`, `my_name`, `my_email`, `reminder_name`, `reminder_email`, `create_date`) VALUES (:reminder_to, :perfect_gift, :cake, :my_name, :my_email, :reminder_name, :reminder_email, now() );";
		if (count($data) > 0) :
			// We want to update it if its already been added
			$sql_str = "UPDATE `landing_data` SET `reminder_to` = :reminder_to, `perfect_gift` = :perfect_gift, `cake` = :cake, `my_name` = :my_name, `my_email` = :my_email, `reminder_name` = :reminder_name, `create_date` = now() WHERE reminder_email = :reminder_email;";
		endif;
		// Execute the sql string
		mysql_execute($sql_str, array(':reminder_to' => $reminder_to, ':perfect_gift' => $perfect_gift, ':cake' => ($cake == "Other" ? $cake_other : $cake), ':my_name' => $my_name, ':my_email' => $my_email, ':reminder_name' => $reminder_name, ':reminder_email' => $reminder_email));

		require_once("./assets/php/sendgrid-php.php");
		
		$options = array("turn_off_ssl_verification" => true);
		$sendgrid = new SendGrid($sendgrid_user, $sendgrid_pass, $options);
		$mail = new SendGrid\Email();
		$mail->addTo($reminder_email)->
			setReplyTo($my_email)->
			setFromName($my_name)->
			setFrom($my_email)->
			setSubject('Subject goes here')->
			setText('Hello. ' . $my_name . ' (' . $my_email . ') wants to send an email to ' .  $reminder_to . ' whose name is ' . $reminder_name. ' (' . $reminder_email . ').  They would like a ' . $perfect_gift . ', but would settle for a ' . $cake . '.')->
			setHtml('<p>Hello.  ' . $my_name . ' (' . $my_email . ') wants to send an email to ' .  $reminder_to . ' whose name is ' . $reminder_name. ' (' . $reminder_email . ').</p><p>They would like a ' . $perfect_gift . ', but would settle for a ' . $cake . '.</p>');
		
		$sendgrid->send($mail);
		
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
<h1>Hint Hint</h1>
<h3>Mother's Day  on May 11<sup>th</sup></h3>
<p>Dear <?= $my_name ?>,</p>
<p>Let&rsquo;s be honest, some people need more than a gentle hint that Mother&rsquo;s Day is just round the corner. So this year we&rsquo;re stepping in to help out, just click on the &ldquo;send a hint&rdquo; button below and we&rsquo;ll give them a nudge on your behalf.</p>
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
	<label for="perfect_gift">My perfect Mother&rsquo;s Day gift would be:</label>
	<select class="form-control" id="perfect-gift" name="perfect_gift">
		<option value="">Choose your perfect gift</option>
		<option<?= $perfect_gift == "A bunch of flowers" ? ' selected="selected" ' : '' ?>>A bunch of flowers</option>
		<option<?= $perfect_gift == "A day without the kids (so you can go shopping)" ? ' selected="selected" ' : '' ?>>A day without the kids (so you can go shopping)</option>
		<option<?= $perfect_gift == "A massage" ? ' selected="selected" ' : '' ?>>A massage</option>
		<option<?= $perfect_gift == "A box of chocolates" ? ' selected="selected" ' : '' ?>>A box of chocolates</option>
		<option<?= $perfect_gift == "A diamond ring" ? ' selected="selected" ' : '' ?>>A diamond ring</option>
		<option<?= $perfect_gift == "Something shiny" ? ' selected="selected" ' : '' ?>>Something shiny</option>
		<option<?= $perfect_gift == "For you to do the housework" ? ' selected="selected" ' : '' ?>>For you to do the housework</option>
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

<div class="row">
	<div class="col-md-6">
		<fieldset class="form-group">
			<label for="reminder-name" id="label-reminder-name">Reminder name</label>
			<input type="text" class="form-control" id="reminder-name" name="reminder_name" value="<?= $reminder_name ?>" maxlength="100" />
		</fieldset>
	</div>
	<div class="col-md-6">
		<fieldset class="form-group">
			<label for="reminder-email" id="label-reminder-email">Reminder email</label>
			<input type="text" class="form-control" id="reminder-email" name="reminder_email" value="<?= $reminder_email ?>" maxlength="100" />
		</fieldset>
	</div>
</div>

<fieldset class="form-group">
	<div class="checkbox">
		<input type="checkbox" id="accept-terms" name="accept_terms" <?= $accept_terms ? 'checked="checked" ' : '' ?>/>
		<label for="accept-terms">By clicking Send Reminder, you are agreeing to the <a href="#">Terms and Conditions</a> and opt in to receive further communication from the Cheesecake Shop.</label>
	</div>
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