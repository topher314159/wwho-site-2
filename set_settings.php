<?php
define('INSTALLFILE', 'set_settings.php');
define('CONFIGFILE', 'mail_settings.php');
define('ABSPATH', dirname(__FILE__) . '/' );

error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

$install_complete = '<br />Settings are saved. <a href="mail_form.htm">Open Mail Form</a>. <br /><br />To change settings, you may login in your cPanel and delete '.CONFIGFILE.' file. Then run <a href="'.INSTALLFILE.'">file again</a>.<br /><br />';

$config_template = 
'<?php

$email_to = "{EMAIL}"; // your email address send TO
$email_from = "{EMAIL2}"; // your email address send FROM
$email_subject = "{SUBJECT}"; 
$thankyou = "{THANKYOU}"; 

?>';

if(isset($_POST['step'])) {
  $step = $_POST['step'];
} else {
  $step = 1;
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Installation</title>
	
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
	.mailform {width: 460px;font-size:12px}
	.style1 {
	font-size: 14;
	font-style: italic;
}
    .style4 {font-size: 14px; font-weight: bold; }
    </style>
	</head>
	<body>
	<div align="center"><br />
	<div class="mailform">
	<div class="mailformheader">Mail Form Settings </div>


    <?php
switch($step) {
  
  
  
  case "1":
  
      if ( file_exists( ABSPATH . CONFIGFILE) ) {

      echo $install_complete;

      } else {

      ?>


      		<script src="validation.js"></script>
			<script>
			required.add('email','EMAIL','Email Address TO');
			required.add('email2','EMAIL','Email Address FROM');
			required.add('subject','NOT_EMPTY','Subject Line');
			required.add('thankyou','NOT_EMPTY','Redirect Page');
			</script>
			<form name="mailform" method="post" action="<?php echo INSTALLFILE; ?>" onSubmit="return validate.check(this)">
			<input type="hidden" name="step" value="2">
			<table width="450px">
			<tr>
			 <td colspan="2">
			 
			 <div class="mailformmessage">
			 <span class="style1">			 Settings will be saved to the following file:<br />
			 </span><br />
			 <em><?php echo ABSPATH.CONFIGFILE; ?>.</em> </td>
			</tr>
			<tr>
			 <td valign="top">
			  <label for="email"><span class="style4">Email Address TO</span><br>
			  <em>emails will be sent to this address </em></label>			 </td>
			 <td valign="top">
			  <input type="text" name="email" id="email" maxlength="100" style="width:180px">
			 </td>
			</tr>

			<tr>
			 <td valign="top">
			  <label for="email2" ><span class="style4">Email Address FROM</span><br>
			  <em>emails will be sent from this email account<br>
			  that should be created in cPanel			  </em></label>			 </td>
			 <td valign="top">
			  <input type="text" name="email2" id="email2" maxlength="100" style="width:180px">
			 </td>
			</tr>

			<tr>
			 <td valign="top">
			  <label for="subject"><span class="style4">Subject (theme)</span><br>
			  <em>All emails from this form<br> 
			  will be sent 
			  with this subject</em></label></td>
			 <td valign="top">
			  <input type="text" name="subject" id="subject" maxlength="100" value="Contact form message" style="width:180px">
			 </td>
			 </tr>

			<tr>
			 <td valign="top">
			  <label for="thankyou"><span class="style4">Page that appears after sending a message</span><br>
			  <em>(<strong>default: thankyou.htm</strong> and you can specify your custom page)</em><br>
</label></td>
			 <td valign="top">
			  <input type="text" name="thankyou" id="thankyou" maxlength="100" value="thankyou.htm" style="width:180px">
			 </td>
			 </tr>

			
			<tr>
			 <td colspan="2" style="text-align:center" >
			 <br /><br />
			  <input type="submit" value="Save Settings" style="width:200px;height:40px">
			  <br /><br />
			  
			 </td>
			</tr>
			</table>
			</form>
			

      <?php
      }  
  
  
  break;
  
  
  
  
  case "2":
  
      $error_strings = array();
      if(!file_exists(ABSPATH.CONFIGFILE)) {
      
        $config_data = str_replace(
          array(
            "{EMAIL}",
	    "{EMAIL2}",
            "{SUBJECT}",
            "{THANKYOU}",
            "{ANSWER}"), 
          array(
            $_POST['email'],
	    $_POST['email2'],
            $_POST['subject'],
            $_POST['thankyou'],
            $_POST['answer']
          ),
          $config_template
        );
        
        // generate config
        if(!$config_h = fopen(ABSPATH.CONFIGFILE,"wb")) {
        	$viewable_code = nl2br(str_replace("<","&lt;",$config_data));
	          $error_strings[] = "Cannot write your configuration file to: ".ABSPATH.CONFIGFILE." - Please change the directory permissions to allow write access.<br /><br /> 
	          If you prefer, you can create the configuration file using the code below:<br /><br />".$viewable_code."<br /><br />Save the above code to a new file at: ".ABSPATH.CONFIGFILE;
	       
        } else {
	        if(!fwrite($config_h, trim($config_data))){
	          $viewable_code = nl2br(str_replace("<","&lt;",$config_data));
	          $error_strings[] = "Cannot write your configuration file to: ".ABSPATH.CONFIGFILE." - Please change the directory permissions to allow write access.<br /><br /> 
	          If you prefer, you can create the configuration file using the code below:<br /><br />".$viewable_code."<br /><br />Save the above code to a new file at: ".ABSPATH.CONFIGFILE;
	        }
	        fclose($config_h);
        }
      }
      
      if(count($error_strings) > 0) {
        foreach($error_strings as $es) {
          echo "$es <br />";
        }
      } else {
        echo $install_complete;
      }
  
  break;
  
  
}
?>
	</div>
	</div>
  </body>
</html>