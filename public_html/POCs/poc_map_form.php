<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//$rootdir = dirname(dirname( __FILE__ ));
//$rootdir = dirname(dirname(dirname( __FILE__ )));
include_once '../includes/subdir.inc.php';

//include_once $rootdir . '/includes/magicquotes.inc.php';
//require_once $rootdir . '/includes/access.inc.php';
include_once '../includes/helpers.inc.php';
//require_once '../PHPMailer-FE_v4.11/_lib/class.phpmailer.php';
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';


//require_once '../PHPMailer-master/class.phpmailer.php';
//require_once '../PHPMailer-master/PHPMailerAutoload.php';

$pagestate = 'form';

// If the contact form has been submitted
if (isset($_POST['action']) and $_POST['action'] == 'maprequest')
{
	$secretKey = "6LfiI14fAAAAAIeswg9tsAK_CwgsV27V5LuUNnU_";
	$token = $_POST['g-token'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$url = "https://www.google.com/recaptcha/api/siteverify";
    $data = array('secret' => $secretKey, 'response' => $token, 'remoteip'=> $ip);
 
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        'secret' => $secretKey,
        'response' => $token
    )
));
$response = curl_exec($curl);
curl_close($curl); 


if(strpos($response, '"success": true') !== FALSE)
	{
	
	
	//include $rootdir . '/includes/db.inc.php';
	
	// Checks the inputted values and sets error messages as required
	if($_POST['name'] == 'Name')
	{
		$contacterror['name'] = 'Please include your name.';
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
		switch($_POST['map'])
		{
			case '1' : $mapdir = 'beddingtonpark'; $mapname = 'Beddington Park'; break;
			case '2' : $mapdir = 'buchancountrypark'; $mapname = 'Buchan Country Park'; break;
			case '3' : $mapdir = 'hortoncountrypark'; $mapname = 'Horton Country Park'; break;
//			case '4' : $mapdir = 'leechpoolandowlbeechwoods'; $mapname = 'Leechpool and Owlbeech Woods'; break;
			case '5' : $mapdir = 'mordenhallpark'; $mapname = 'Morden Hall Park'; break;
			case '6' : $mapdir = 'nonsuchpark'; $mapname = 'Nonsuch Park'; break;
			case '7' : $mapdir = 'norburypark'; $mapname = 'Norbury Park'; break;
			case '8' : $mapdir = 'norkpark'; $mapname = 'Nork Park'; break;
			case '9' : $mapdir = 'oakspark'; $mapname = 'Oaks Park'; break;
			case '10' : $mapdir = 'ranmore'; $mapname = 'Ranmore'; break;
			case '11' : $mapdir = 'redhillcommon'; $mapname = 'Redhill Common'; break;
		}
						
		$mailmessage = "Dear " . $_POST['name'] . ",\n\n Thank you for your interest in the Mole Valley Orienteering Club and for requesting a copy of the map for one of our Permanent Orienteering Courses. Please find attached a PDF copy of the map. Should you have any questions, please get in touch using chair@mvoc.org or pocs@mvoc.org\n\n Kind regards,\nMole Valley Orienteering Club";
		$mailmessage = wordwrap($mailmessage, 70);

//		echo $mailmessage;

		$email = new PHPMailer();

		
		$email->From      = 'pocs@mvoc.org';
		$email->FromName  = 'Mole Valley Orienteering Club';
		$email->Subject   = 'MVOC Permanent Orienteering Map:- ' . $mapname;
		$email->Body      = $mailmessage;
		$email->AddAddress( $_POST['email'] );
		
		$file_to_attach = dirname(__DIR__).'/POCs/maps/' . $mapdir . '.pdf';
		$email->AddAttachment( $file_to_attach , $mapname . ' Map.pdf' );

		$file_to_attach = dirname(__DIR__).'/POCs/maps/' . $mapdir . 'notes.pdf';
		$email->AddAttachment( $file_to_attach , $mapname . ' Notes.pdf' );
	
	
// debug code		
//		if(!$email->send()) {
//			echo 'Message could not be sent.';
//			echo 'Mailer Error: ' . $email->ErrorInfo;
//		} else {
//			echo 'Message has been sent';
//		}		
// end		
		
		if($email->Send()) {
					$pagestate = 'thankyou';	
		}
		else {
					$pagestate = 'emailerror';
		}
		
		$email = new PHPMailer();
		
		$mailmessage = date('d/m/Y') . ', ' . $_POST['name'] . ', ' . $_POST['email'] . ', ' . $mapname;
		
		$email->From      = 'pocs@mvoc.org';
		$email->FromName  = 'Mole Valley Orienteering Club';
		$email->Subject   = 'MVOC Map Download:- ' . $mapname;
		$email->Body      = $mailmessage;
		$email->AddAddress( 'pocs@mvoc.org' );
		$email->Send();
					
	}
	}
	else
	{
		$pagestate = 'validationerror';
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Permanent O Courses - Map Form</title>
<link rel="stylesheet" type="text/css" href="../Style%20Sheets/whole%20site%20style.css">

<script src="https://www.google.com/recaptcha/api.js?render=6LfiI14fAAAAAEOhYhX-0oDNQNQnFmCXrQ7tvoq3"></script>

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
      <img border="0" src="../images/Header%20images/MVlogo%20100%20x%20100.jpg" width="100" height="100"></a></td>
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
      <a href="permanent_Orienteering_Courses.htm">Permanent O Courses</a><font color="#FF00FF">&nbsp;&nbsp;
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
PERMANENT ORIENTEERING COURSES</h2>
<div align="center">
  <center>
  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="80%" bordercolorlight="#FFFFFF" bordercolordark="#FFFFFF">
    <tr>
      <td width="100%">
      <?php if($pagestate == 'form') {?>
  			<h3>Map Request Form</h3>
    		<p>Please complete the form below and submit.</p>
    		<p>You will  receive an email with a PDF copy of the map and some instructions attached for your personal use.</p>
    		<p>Should you have any questions regarding orienteering or the Permanent Orienteering Courses, we would love to hear from you, so please get in touch using chairATmvoc.org or pocsATmvoc.org.</p>
    		<p>General information regarding the sport of Orienteering can be found on the British Orienteering Foundation's website, <a href="http://www.britishorienteering.org.uk" target="new">here</a>.</p>
            <?php } 
			 elseif($pagestate == 'thankyou') {?>
  			<h3>Thank you - Map Request Form Submitted</h3>
    		<p>You should shortly receive an email with a PDF copy of the map and some instructions attached.</p>
    		<p>Download another?</p>
			<?php }
			elseif($pagestate == 'validationerror') {?>
  			<h3>Captcha validation error</h3>
    		<p>Please try again</p>
			<?php }?>

		
                <form id="maprequest" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                	<input type="hidden" id="g-token" name="g-token" />
      				<table>
                    
                        <tr class="marginbotlg">
                            <td class="label">Map:</td>
                            <td colspan="2">
                            <select name="map" class="round_sb">
                            	<option value="1" <?php if((isset($contacterror) && $_POST['map'] == '1') || (isset($_GET['map']) && $_GET['map'] == '1')) htmlout('selected="selected"'); ?>>Beddington Park</option>
                            	<option value="2" <?php if((isset($contacterror) && $_POST['map'] == '2') || (isset($_GET['map']) && $_GET['map'] == '2')) htmlout('selected="selected"'); ?>>Buchan Country Park</option>
                            	<option value="3" <?php if((isset($contacterror) && $_POST['map'] == '3') || (isset($_GET['map']) && $_GET['map'] == '3')) htmlout('selected="selected"'); ?>>Horton Country Park</option>
<!--                            	<option value="4" <?php if((isset($contacterror) && $_POST['map'] == '4') || (isset($_GET['map']) && $_GET['map'] == '4')) htmlout('selected="selected"'); ?>>Leechpool and Owlbeech Woods</option>-->
                            	<option value="5" <?php if((isset($contacterror) && $_POST['map'] == '5') || (isset($_GET['map']) && $_GET['map'] == '5')) htmlout('selected="selected"'); ?>>Morden Hall Park</option>
                            	<option value="6" <?php if((isset($contacterror) && $_POST['map'] == '6') || (isset($_GET['map']) && $_GET['map'] == '6')) htmlout('selected="selected"'); ?>>Nonsuch Park</option>
                            	<option value="7" <?php if((isset($contacterror) && $_POST['map'] == '7') || (isset($_GET['map']) && $_GET['map'] == '7')) htmlout('selected="selected"'); ?>>Norbury Park</option>
                            	<option value="8" <?php if((isset($contacterror) && $_POST['map'] == '8') || (isset($_GET['map']) && $_GET['map'] == '8')) htmlout('selected="selected"'); ?>>Nork Park</option>
                            	<option value="9" <?php if((isset($contacterror) && $_POST['map'] == '9') || (isset($_GET['map']) && $_GET['map'] == '9')) htmlout('selected="selected"'); ?>>Oaks Park</option>
                            	<option value="10" <?php if((isset($contacterror) && $_POST['map'] == '10') || (isset($_GET['map']) && $_GET['map'] == '10')) htmlout('selected="selected"'); ?>>Ranmore Common</option>
                            	<option value="11" <?php if((isset($contacterror) && $_POST['map'] == '11') || (isset($_GET['map']) && $_GET['map'] == '11')) htmlout('selected="selected"'); ?>>Redhill Common</option>
                            </select>
                        </tr>
      					<tr class="marginbotlg">
            				<td class="label">Name:</td>
                			<td><input type="text" name="name" id="name" value="<?php if(isset($contacterror)) { htmlout($_POST['name']); } else { htmlout(''); } ?>" class="<?php if(!isset($contacterror)) { htmlout('default-value'); } ?>"/></td>
                            <?php if(isset($contacterror['name'])) { ?>
                                <td class="error"><div><?php  htmlout($contacterror['name']); ?></div></td>
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

<p align="center"><font face="Arial"><img border="0" src="../images/swallow%2025.jpg" width="19" height="19">
 Mole Valley Orienteering Club <img border="0" src="../images/swallow%2025.jpg" width="19" height="19">
</font>
</p>
<p align="center"><font face="Arial">Back to <a href="http://www.mvoc.org">home page</a></font></p>

</td></tr><!--msnavigation--></table>

<script>
grecaptcha.ready(function() {
    grecaptcha.execute('6LfiI14fAAAAAEOhYhX-0oDNQNQnFmCXrQ7tvoq3', {action: 'homepage'}).then(function(token) {
        console.log(token);
       document.getElementById("g-token").value = token;
    });
});
</script>
</body></html>