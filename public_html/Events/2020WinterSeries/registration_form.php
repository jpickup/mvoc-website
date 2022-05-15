<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//$rootdir = dirname(dirname( __FILE__ ));
//$rootdir = dirname(dirname(dirname( __FILE__ )));
include_once '../../includes/subdir.inc.php';

//include_once $rootdir . '/includes/magicquotes.inc.php';
//require_once $rootdir . '/includes/access.inc.php';
include_once '../../includes/helpers.inc.php';
//require_once '../PHPMailer-FE_v4.11/_lib/class.phpmailer.php';
require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';


//require_once '../PHPMailer-master/class.phpmailer.php';
//require_once '../PHPMailer-master/PHPMailerAutoload.php';

if (isset($_GET['event']))
{
	$event = $_GET['event'];
	//echo('### ' . $event . ' ###');
		switch($event)
		{
			case 'tolworth' : include 'tolworth_details.inc.php'; break;
			case 'epsom' : include 'epsom_details.inc.php'; break;
			case 'old_malden' : include 'old_malden_details.inc.php'; break;
			default : echo('not found');
		}
}

//Event Details  - change these for each event
//$mapname = 'epsom';
//$organiser = 'Daniel Sullivan';
//$organiseremail = 'dansullivan2001@gmail.com';
//$eventmonthyear = 'Jan 21';
//$startdate = '2021-04-01';
//$finishdate = '2021-04-10';
//$startlocation = 'Anderson Close, Epsom, KT19 8LY Link to Google Maps: https://goo.gl/maps/2ERJxJk36NLJj6C89';
//$mappin = '6731';

//date formatting
$startdateformattedfortxt = date("d/m/Y", strtotime($startdate));
$finishdateformattedfortxt = date("d/m/Y", strtotime($finishdate));
$startdateformatted = new DateTime($startdate);
$finishdateformatted = new DateTime($finishdate);


// Checks if the event is open
$datenow = new DateTime("now");
if ($datenow < $startdateformatted) {
$pagestate = 'eventnotopen';
}
elseif ($datenow > $finishdateformatted) {
$pagestate = 'eventclosed';	
}
else $pagestate = 'form';


// If the contact form has been submitted
if (isset($_POST['action']) and $_POST['action'] == 'maprequest')
{
	//echo('### processing map request ###');
	
	//include $rootdir . '/includes/db.inc.php';
	
	// Checks the inputted values and sets error messages as required
	if($_POST['name'] == 'Name' or empty($_POST['name']))
	{
		$contacterror['name'] = 'Please include your name.';
	}
	if($_POST['club'] == 'Club' or empty($_POST['club']))
	{
		$contacterror['club'] = 'Please include your club.';
	}
	if(!preg_match("/[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $_POST['email']))
	{               
		$contacterror['email'] = 'Please enter a valid email address.';
	}

	if(isset($contacterror))
	{
		$pagestate = 'form';
	}
	else
	{
		//echo('### no errors ###');
		//switch($_POST['map'])
		//{
		//	case '1' : $mapdir = 'beddingtonpark'; $mapname = 'Beddington Park'; break;
		//	case '2' : $mapdir = 'buchancountrypark'; $mapname = 'Buchan Country Park'; break;
		//	case '3' : $mapdir = 'hortoncountrypark'; $mapname = 'Horton Country Park'; break;
//			case '4' : $mapdir = 'leechpoolandowlbeechwoods'; $mapname = 'Leechpool and Owlbeech Woods'; break;
		//	case '5' : $mapdir = 'mordenhallpark'; $mapname = 'Morden Hall Park'; break;
		//	case '6' : $mapdir = 'nonsuchpark'; $mapname = 'Nonsuch Park'; break;
		//	case '7' : $mapdir = 'norburypark'; $mapname = 'Norbury Park'; break;
		//	case '8' : $mapdir = 'norkpark'; $mapname = 'Nork Park'; break;
		//	case '9' : $mapdir = 'oakspark'; $mapname = 'Oaks Park'; break;
		//	case '10' : $mapdir = 'ranmore'; $mapname = 'Ranmore'; break;
		//	case '11' : $mapdir = 'redhillcommon'; $mapname = 'Redhill Common'; break;
		//}
		
		$mailmessage = "Dear " . $_POST['name'] . ",\n\n Thank you for entering this event.\n\nThe course is available on the MapRun app and your first attempt will count towards the results if you participate between 00.00 on " . $startdateformattedfortxt . " and 23.59 on " . $finishdateformattedfortxt . ". You can attempt the course at any time you like but please try to avoid peak times on the roads for rush hour or school drop off/pickup.\n\nThe map and control descriptions are attached, please print these off and use them to navigate rather than the map on the app. The controls are GPS located on various objects such as lamp posts, telegraph poles and post boxes. To replicate a normal Street O event, the map should not be looked at until the clock has started.\n\nThe course is a one-hour score event. Points will be awarded according to the first number of each control (so controls 10, 11 etc. are worth 10 points; 20, 21 etc. are worth 20 points and so on.) There is a tough 30 point per minute penalty for lateness so it pays to be back on time! You can try the course as many times as you like but only your first attempt counts in the results.\n\nThe event specific safety notes are attached to this email. YOU MUST READ THESE NOTES BEFORE ATTEMPTING THE COURSE.\n\nEnsure that you have the latest version of MapRun installed on your phone and find this event by selecting UK > Mole Valley > StreetO 20-21 Winter Series > " . ucwords($mapname) . " " . $eventmonthyear . ". It is advisable to do this before you leave home to ensure you have a data signal to download the course.\n\nWhen ready press 'Go To Start' and the app will ask you for a PIN to access the course. The password to access the course on the app is " . $mappin . ".\n\nNow approach the start location, the app will notify you when you are there. You will then have one hour to score as many points as possible and return to the start/finish control.\n\nPlease note that if on the way to another control you run past the start/finish this may prematurely end your timed run, so be careful to avoid this.\n\nMore detailed guidance on how to use the app is attached to this email and can also be found on the MapRunners website http://maprunners.weebly.com/ including details of how you can run using MapRunG if you have a Garmin watch.\n\nIf you have a problem with the app not registering a control you believe you have visited, please email me.\n\nStart/finish location: " . $startlocation . "\n\nThe results will be posted on the MVOC website as soon as possible after the final day. Live results can be viewed on the app or on the MapRunners website.\n\nIf you enjoy the course, please try some of our training courses that are available on the app with details on the MVOC website http://www.mvoc.org/virtualcourses/index.htm\n\nIf you have any questions, please ask.\n\nKind Regards,\n" . $organiser;
		
		$mailmessage = $mailmessage . "\n\n" . date('d/m/Y') . ', ' . $_POST['name'] . ', ' . $_POST['club'] . ', ' . $_POST['email'] . ', ' . ucwords($mapname);



//$mailmessage = "Test message";	
		//echo $mailmessage;
		
						
//		$mailmessage = "Dear " . $_POST['name'] . ",\n\n Thank you for your interest in the Mole Valley Orienteering Club and for requesting a copy of the map for one of our Permanent Orienteering Courses. Please find attached a PDF copy of the map. Should you have any questions, please get in touch using chair@mvoc.org or pocs@mvoc.org\n\n Kind regards,\nMole Valley Orienteering Club";
		//$mailmessage = wordwrap($mailmessage, 210);

		//echo $mailmessage;

		$email = new PHPMailer;
		
		$email->From      = $organiseremail;
		$email->FromName  = $organiser;
		$email->Subject   = 'MVOC StreetO 2020 - ' . ucwords($mapname);
		$email->Body      = $mailmessage;
		$email->AddAddress( $_POST['email']);
		$email->AddAddress( $organiseremail);

		$file_to_attach = dirname( __FILE__ ) . '/maps/' . $mapname . '-notes.pdf';
		$email->AddAttachment( $file_to_attach , ucwords($mapname) . ' Important Notes.pdf' );
		
		$file_to_attach = dirname( __FILE__ ) . '/maps/' . $mapname . '-map.pdf';
		$email->AddAttachment( $file_to_attach , ucwords($mapname) . ' Map.pdf' );

		$file_to_attach = dirname( __FILE__ ) . '/maps/' . $mapname . '-controls.pdf';
		$email->AddAttachment( $file_to_attach , ucwords($mapname) . ' Control Description.pdf' );
		
		$file_to_attach = dirname( __FILE__ ) . '/maps/MVOC - MapRunF instructions 20-21 Street O.pdf';
		$email->AddAttachment( $file_to_attach , 'MVOC - MapRunF instructions 20-21 Street O.pdf' );		
		
//		if(!$email->send()) {
//			echo 'Message could not be sent.';
//			echo 'Mailer Error: ' . $email->ErrorInfo;
//		} else {
//			echo 'Message has been sent';
//		}		
//		
		if($email->Send()) {
					$pagestate = 'thankyou';	
					//echo($pagestate);
		}
		else {
					$pagestate = 'emailerror';
					//echo($pagestate);
		}
		
		$email = new PHPMailer;
		
		$mailmessage = date('d/m/Y') . ', ' . $_POST['name'] . ', ' . $_POST['club'] . ', ' . $_POST['email'] . ', ' . ucwords($mapname);
		
//		echo $mailmessage;
				
		$email->From      = $organiseremail;
		$email->FromName  = 'Auto Email';
		$email->Subject   = 'Registration MVOC StreetO 2020 - ' . ucwords($mapname);
		$email->Body      = $mailmessage;
		$email->AddAddress( $organiseremail );
		$email->AddAddress( 'dansullivan2001@gmail.com' );
		$email->Send();
		
//		echo count($email->getToAddresses());
		
//		if(!$email->send()) {
//			echo 'Message could not be sent.';
//			echo 'Mailer Error: ' . $email->ErrorInfo;
//		} else {
//			echo 'Message has been sent';
//		}		
		
					
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Street O Registration Form</title>
<link rel="stylesheet" type="text/css" href="../Style%20Sheets/whole%20site%20style.css">
</head>

<?php

?>


<body><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td>

<div align="center">
  <center>
  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="775" id="AutoNumber5" height="1" align="left">
    <tr>
      <td width="132" height="2">
      <a href="../index.html">
      <img border="0" src="../../images/Header%20images/MVlogo%20100%20x%20100.jpg" width="100" height="100"></a></td>
      <td width="1418" height="2">
      <p align="center"><font face="Arial" style="font-size: 20pt"><strong>MOLE 
      VALLEY ORIENTEERING CLUB</strong></font><font face="Arial" color="#FF00FF"><br>
      |&nbsp;&nbsp;
    </font><font face="Arial"> <a href="../index.html">Home Page</a>&nbsp;&nbsp; </font><font face="Arial" color="#FF00FF">|&nbsp; &nbsp;</font><font face="Arial"><a href="../The%20Mole%20Roll/The%20Mole%20Roll.htm">The Mole Roll</a>&nbsp;&nbsp; </font><font face="Arial" color="#FF00FF">|&nbsp; &nbsp;</font><font face="Arial"><a href="../Results%20main%20page.htm">Event 
    Results</a> &nbsp; </font><font face="Arial" color="#FF00FF">|
      <br>
      |&nbsp;&nbsp;
      </font>
      <font face="Arial">
      <a href="permanent_Orienteering_Courses.htm">Street O Registration Form</a><font color="#FF00FF">&nbsp;&nbsp;
      </font></font><font face="Arial" color="#FF00FF">|&nbsp;&nbsp; </font>
    <font face="Arial"><a href="../Novice%20guide.htm">Your First Orienteering 
      Event</a><font color="#FF00FF">&nbsp;&nbsp; </font></font><font face="Arial" color="#FF00FF">
      |
      <br>
      |&nbsp;&nbsp;&nbsp;
      </font>
      <font face="Arial"><a href="http://www.mvoc.org/Jargon_Buster.htm">O 
    Jargon Buster</a>&nbsp; <font color="#FF00FF">&nbsp;</font></font><font face="Arial" color="#FF00FF">|&nbsp; </font>
      <font face="Arial">&nbsp;<a href="http://www.mvoc.org/Events_Calendar.htm">O Events Calendar</a><font color="#FF00FF">&nbsp; </font> </font> <font face="Arial" color="#FF00FF">
      &nbsp;|<br>
      |&nbsp;&nbsp; </font><font face="Arial"><a href="../Join%20Mole%20Valley.htm">
    Join Mole Valley</a><font color="#FF00FF"> &nbsp;&nbsp;</font></font><font face="Arial" color="#FF00FF">|&nbsp;&nbsp; </font><font face="Arial">
      <a href="../Club%20Management/Club%20management%20and%20policies.htm">Club 
      Management</a>&nbsp;&nbsp; </font> <font face="Arial" color="#FF00FF">| </font><font face="Arial">&nbsp; </font><font face="Arial" color="#FF00FF">
      <a href="../Club%20Management/Club%20Contacts.htm">Club Contacts</a>&nbsp;&nbsp; | </font></p>
      </td>
    </tr>
    </table>
  </center>
</div>

</td></tr><!--msnavigation--></table><!--msnavigation--><table dir="ltr" border="0" cellpadding="0" cellspacing="0" width="100%"><tr><!--msnavigation--><td valign="top">

<h2 align="center"><br>
<?php htmlout(ucwords($mapname)) ?> STREET O REGISTRATION</h2>
<div align="center">
  <center>
  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="80%" bordercolorlight="#FFFFFF" bordercolordark="#FFFFFF">
    <tr>
      <td width="100%">
      <?php if($pagestate == 'eventnotopen') {?>
  			<h3>Street O Registration Form</h3>
			<p> Registration for this event opens on the <?php htmlout($startdateformattedfortxt) ?>.
      <?php }
	  elseif($pagestate == 'eventclosed') {?>
  			<h3>Street O Registration Form</h3>
			<p> Registration for this event is now closed.	
            <?php htmlout($finishdateformatted)?>	
            <?php htmlout($datenow)?>	
      <?php }
	  elseif($pagestate == 'form') { ?>
  			<h3>Street O Registration Form</h3>
    		<p>Please complete the form below and submit. PLEASE CHECK YOUR SPAM FOLDER!</p>
    		<p>You will  receive an email with a PDF copy of the map and some instructions attached for your personal use.</p>
    		<p>Should you have any questions regarding orienteering, we would love to hear from you, so please get in touch using chairATmvoc.org</p>
                <form id="maprequest" action="" method="post" autocomplete="off" enctype="multipart/form-data">
      				<table>
                    
      					<tr class="marginbotlg">
            				<td class="label">Name:</td>
                			<td><input type="text" name="name" id="name" value="<?php if(isset($contacterror)) { htmlout($_POST['name']); } else { htmlout(''); } ?>" class="<?php if(!isset($contacterror)) { htmlout('default-value'); } ?>"/></td>
                            <?php if(isset($contacterror['name'])) { ?>
                                <td class="error"><div><?php  htmlout($contacterror['name']); ?></div></td>
                            <?php } ?>
                        </tr>
      					<tr class="marginbotlg">
            				<td class="label">Club: *enter IND for non-BOF members</td>
                			<td><input type="text" name="club" id="name" value="<?php if(isset($contacterror)) { htmlout($_POST['club']); } else { htmlout(''); } ?>" class="<?php if(!isset($contacterror)) { htmlout('default-value'); } ?>"/></td>
                            <?php if(isset($contacterror['club'])) { ?>
                                <td class="error"><div><?php  htmlout($contacterror['club']); ?></div></td>
                            <?php } ?>
                        </tr>
                        <tr class="marginbotlg">
                            <td class="label">Email Address:</td>
                            <td><input type="text" name="email" id="email" value="<?php if(isset($contacterror)) { htmlout($_POST['email']); } ?>" class="<?php if(!isset($contacterror)) { htmlout('default-value'); } ?>"/></td>
                            
                            <?php if(isset($contacterror['email'])) { ?>
                                <td class="error"><div><?php  htmlout($contacterror['email']); ?></div></td>
                            <?php } ?>
                        </tr>
                        
                    </table>
                  	</br>
        			<table>
          				<input type="hidden" name="action" value="maprequest" />
          				<tr><td class="label"></td><td><input type="submit" value="Submit" class="submitbtn"/></td></tr>
          			</table>
      			</form>
            <?php } 
			 elseif($pagestate == 'thankyou') {?>
  			<h3>Thank you - Registration Form Submitted</h3>
    		<p>You should shortly receive an email with a PDF copy of the map and some instructions attached. PLEASE CHECK YOUR SPAM FOLDER!</p>
<?php }?>

		
      </td>
    </tr>
  </table>
  </center>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>go to <a href="permanent_Orienteering_Courses.htm">top</a> of this page go to
top of <a href="../index.html">home page</a></p>
&nbsp;<!--msnavigation--></td></tr><!--msnavigation--></table><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td>

<hr>

<p align="center"><font face="Arial"><img border="0" src="../../images/swallow%2025.jpg" width="19" height="19">
 Mole Valley Orienteering Club <img border="0" src="../../images/swallow%2025.jpg" width="19" height="19">
</font>
</p>
<p align="center"><font face="Arial">Back to <a href="http://www.mvoc.org">home page</a></font></p>

</td></tr><!--msnavigation--></table></body></html>