<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$url = JURI::base();

//Email Parameters
$recipient1 = $params->get('department_mail_1', '');
$department1 = $params->get('department_name_1', '');
$recipient2 = $params->get('department_mail_2', '');
$department2 = $params->get('department_name_2', '');
$recipient3 = $params->get('department_mail_3', '');
$department3 = $params->get('department_name_3', '');
$recipient4 = $params->get('department_mail_4', '');
$department4 = $params->get('department_name_4', '');
$recipient5 = $params->get('department_mail_5', '');
$department5 = $params->get('department_name_5', '');

$fromName = @$params->get('from_name', 'Rapid Contact');
$fromEmail = @$params->get('from_email', 'rapid_contact@yoursite.com');

// Text Parameters
$myNameLabel = $params->get('name_label', 'Name:');
$myEmailLabel = $params->get('email_label', 'Email:');
$mySubjectLabel = $params->get('subject_label', 'Subject:');
$myMessageLabel = $params->get('message_label', 'Message:');
$buttonText = $params->get('button_text', 'Send Message');
$pageText = $params->get('page_text', 'Thank you for your contact.');
$errorText = $params->get('error_text', 'Your message could not be sent. Please try again.');
$noEmail = $params->get('no_email', 'Please write your email');
$invalidEmail = $params->get('invalid_email', 'Please write a valid email');
$wrongantispamanswer = $params->get('wrong_antispam', 'Wrong anti-spam answer');
$pre_text = $params->get('pre_text', '');

// URL Parameters
$exact_url = $params->get('exact_url', true);
$disable_https = $params->get('disable_https', true);
$fixed_url = $params->get('fixed_url', true);
$myFixedURL = $params->get('fixed_url_address', '');

// Anti-spam Parameters
$enable_anti_spam = $params->get('enable_anti_spam', true);
$myAntiSpamQuestion = $params->get('anti_spam_q', 'How many eyes has a typical person?');
$myAntiSpamAnswer = $params->get('anti_spam_a', '2');

// Module Class Suffix Parameter
$mod_class_suffix = $params->get('moduleclass_sfx', '');


if ($fixed_url) {
  $url = $myFixedURL;
}
else {
  if (!$exact_url) {
    $url = JURI::current();
  }
  else {
    if (!$disable_https) {
      $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }
    else {
      $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }
  }
}

$myError = '';
$CORRECT_ANTISPAM_ANSWER = '';
$CORRECT_NAME = '';
$CORRECT_EMAIL = '';
$CORRECT_SUBJECT = '';
$CORRECT_MESSAGE = '';

if (isset($_POST["rp_email"])) {
	$CORRECT_NAME = $_POST["rp_name"];
	$CORRECT_SUBJECT = $_POST["rp_subject"];
	$CORRECT_MESSAGE = $_POST["rp_message"];
	
  // check anti-spam
  if ($enable_anti_spam) {
    if ($_POST["rp_anti_spam_answer"] != $myAntiSpamAnswer) {
      $myError = '<div class="error">' . $wrongantispamanswer . '</div>';
    }
    else {
      $CORRECT_ANTISPAM_ANSWER = $_POST["rp_anti_spam_answer"];
    }
  }
  // check email
  if ($_POST["rp_email"] === "") {
    $myError = '<div class="error">' . $noEmail . '</div>';
  }
  if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", strtolower($_POST["rp_email"]))) {
    $myError = '<div class="error">' . $invalidEmail . '</div>';
  }
  else {
    $CORRECT_EMAIL = $_POST["rp_email"];
  }

  if ($myError == '') {
    $mySubject = $_POST["rp_subject"];
    $myMessage = 'You received a message from '. $_POST["rp_name"] .' ('. $_POST["rp_email"] .")\n\n". $_POST["rp_message"] ."\n\n(".$url.')';

    $mailSender = &JFactory::getMailer();
	
	if($department2 != '' || $department3 != '' || $department4 != '' || $department5 != '') {
		$tomail = $_POST["department"];
		switch($tomail) {
			case"1":
				$tomail = $recipient1;
			break;
			case"2":
				$tomail = $recipient2;
			break;
			case"3":
				$tomail = $recipient3;
			break;
			case"4":
				$tomail = $recipient4;
			break;
			case"5":
				$tomail = $recipient5;
			break;
		}
	} else {
		$tomail = $recipient1;
	}
	
	
	
    $mailSender->addRecipient($tomail);

    $mailSender->setSender(array($fromEmail,$fromName));
    $mailSender->addReplyTo(array( $_POST["rp_email"], '' ));

    $mailSender->setSubject($mySubject);
    $mailSender->setBody($myMessage);

    if ($mailSender->Send() !== true) {
      $myReplacement = '<div class="error">' . $errorText . '</div>';
      print $myReplacement;
      return true;
    }
    else {
      $myReplacement = '<div class="success">' . $pageText . '</div>';
      print $myReplacement;
      return true;
    }

  }
} // end if posted

// check recipient
if ($recipient1 === "") {
  $myReplacement = '<div class="error">No recipient specified</div>';
  print $myReplacement;
  return true;
}

print '<div id="contact_form" class="rapid_contact ' . $mod_class_suffix . '">
<form action="' . $url . '" method="post">';
if($pre_text != '') echo '<div class="note ">'.$pre_text.'</div>';

if ($myError != '') {
  print $myError;
}

echo '
<div><label for="rp_name">' . $myNameLabel . '</label><input class=" inputbox " type="text" id="rp_name" name="rp_name" value="'.$CORRECT_NAME.'" /></div>
<div><label for="rp_email">' . $myEmailLabel . '</label><input class=" inputbox " type="text" name="rp_email" id="rp_email" value="'.$CORRECT_EMAIL.'" /></div>
<div><label for="rp_subject">' . $mySubjectLabel . '</label><input class=" inputbox " type="text" name="rp_subject" id="rp_subject" value="'.$CORRECT_SUBJECT.'" /></div>';

if($department2 != '' || $department3 != '' || $department4 != '' || $department5 != '') {

echo '
<div>
<label for="department" class="department">Department</label>
<select name="department" id="department">
	<option value="1">'.$department1.'</option>';
	if($department2 != "") { echo '<option value="2">'.$department2.'</option>'; }
	if($department3 != "") { echo '<option value="3">'.$department3.'</option>'; }
	if($department4 != "") { echo '<option value="4">'.$department4.'</option>'; }
	if($department5 != "") { echo '<option value="5">'.$department5.'</option>'; }
echo '	
</select>
</div>
	';
}
echo '<div><label for="rp_message">' . $myMessageLabel . '</label><textarea class=" textarea " name="rp_message" id="rp_message">'.$CORRECT_MESSAGE.'</textarea></div>
';
if ($enable_anti_spam) {
    print '<div><label class="simple-label" for="rp_anti_spam_answer">' . $myAntiSpamQuestion . '</label><input class=" captcha inputbox " type="text" name="rp_anti_spam_answer" id="rp_anti_spam_answer" value="'.$CORRECT_ANTISPAM_ANSWER.'"/></div>';
}
print '<div><input class=" button " id="submit-form" type="submit" name="submit" value="' . $buttonText . '" /></div></form></div>' . "\n";
return true;

	/*$mailSender = &JFactory::getMailer();
    $mailSender->addRecipient('hogash.tf@gmail.com');

    $mailSender->setSender(array('mhogas@gmail.com','Marius'));
    $mailSender->addReplyTo('gigi@gigi.ro');

    $mailSender->setSubject('subiect');
    $mailSender->setBody('body message');
	$mailSender->Send();*/
	

?>