<?php
if($_POST)
{
	$to_Email   	= "cstubbs@advisorsacademy.com"; //Replace with recipient email address
	$subject        = 'Website Order Form'; //Subject line for emails
	
	//check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	
		//exit script outputting json data
		$output = json_encode(
		array(
			'type'=>'error', 
			'text' => 'Request must come from Ajax'
		));
		
		die($output);
    } 
	
	//check $_POST vars are set, exit if any missing
	if(!isset($_POST["userFname"]) || !isset($_POST["userLname"]) || !isset($_POST["userSocialmedia"]) || !isset($_POST["userContent"]) || !isset($_POST["userVideos"]) || !isset($_POST["userWebinfo"]))
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
		die($output);
	}

	//Sanitize input data using PHP filter_var().
	$user_Fname        = filter_var($_POST["userFname"], FILTER_SANITIZE_STRING);
    $user_Lname        = filter_var($_POST["userLname"], FILTER_SANITIZE_STRING);
	$user_Socialmedia  = filter_var($_POST["userSocialmedia"], FILTER_SANITIZE_EMAIL);
	$user_Content      = filter_var($_POST["userContent"], FILTER_SANITIZE_STRING);
	$user_Video        = filter_var($_POST["userVideos"], FILTER_SANITIZE_STRING);
    $user_Webinfo      = filter_var($_POST["userWebinfo"], FILTER_SANITIZE_STRING);
	
    
    //Email
    $user_Message = '
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;"><tbody><tr><td align="center" bgcolor="#ffffff" style="padding: 40px 0 30px 0; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
							<img src="http://advisors-academy.com/wp-content/uploads/logo.png" alt="Advisor\'s Academy Logo" width="300" height="138" style="display: block; background: #ffffff;"></td>
					</tr><tr><td bgcolor="#FAEDDC" style="padding: 40px 30px 40px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
										<b>Website Order from '.$user_Fname.' '.$user_Lname.'!</b>
									</td>
								</tr><tr><td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
										<b>Content for Website</b> <br /> '.$user_Content.'
									</td>
								</tr><tr><td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
										<b>Website Information:</b> '.$user_Webinfo.' 
									</td>
								</tr><tr><td>
										<table border="0" cellpadding="0" cellspacing="0" width="100%" style="word-break: break-word;"><tbody><tr><td width="260" style="background: #ffffff; padding: 10px;">
													<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="
    font-size: 16px;
    border-bottom: 1px solid #aaa;
    padding: 5px;
">
																
                                                                <b>Social Media</b>
															</td>
														</tr><tr><td style="padding: 25px 0 0 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px; height: 100px;">
																'.$user_Socialmedia.'
															</td>
														</tr></tbody></table></td>
												<td style="font-size: 0; line-height: 0;" width="20">
													&nbsp;
												</td>
												<td width="260" style="background: #ffffff; padding: 10px;">
													<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="
    font-size: 16px; border-bottom: 1px solid #aaa; padding: 5px;
">
																
                                                                <b style="  
">Video Scheduling</b>
															</td>
														</tr><tr><td style="padding: 25px 0 0 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px; height: 100px;">
																'.$user_Video.'
															</td>
														</tr></tbody></table></td>
											</tr></tbody></table></td>
								</tr></tbody></table></td>
					</tr><tr><td bgcolor="#ee4c50" style="padding: 30px 30px 30px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;">
								&reg; Advisor\'s Academy 2014<br><a href="javascript:void(0);" style="color: #ffffff;" onclick="$Widgets.Email.Message.evMoveToAnchor(this);" _anchor="#"><font color="#ffffff">Visit</font></a> our website
									</td>
									<td align="right">
										<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
													<a href="http://www.twitter.com/" style="color: #ffffff;" target="_blank" title="This external link will open in a new window">
														<img src="http://www.nightjar.com.au/tests/magic/images/tw.gif" alt="Twitter" width="38" height="38" style="display: block;" border="0"></a>
												</td>
												<td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
												<td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
													<a href="https://www.facebook.com/pages/Advisors-Academy/155070514532486" style="color: #ffffff;" target="_blank" title="This external link will open in a new window">
														<img src="http://www.nightjar.com.au/tests/magic/images/fb.gif" alt="Facebook" width="38" height="38" style="display: block;" border="0"></a>
												</td>
											</tr></tbody></table></td>
								</tr></tbody></table></td>
					</tr></tbody></table>';
    
	//additional php validation
	if(strlen($user_Fname)<2) // If length is less than 2 it will throw an HTTP error.
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
		die($output);
	}
    if(strlen($user_Lname)<2) // If length is less than 2 it will throw an HTTP error.
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
		die($output);
	}
    /*
	if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
		die($output);
	}
    
	if(!is_numeric($user_Phone)) //check entered data is numbers
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Only numbers allowed in phone field'));
		die($output);
	}
    */
	if(strlen($user_Socialmedia)<5) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Too short! Please enter social media information.'));
		die($output);
	}
    if(strlen($user_Content)<5) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Too short! Please enter more content.'));
		die($output);
	}
    if(strlen($user_Video)<5) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Too short! Please enter full dates.'));
		die($output);
	}
    if(strlen($user_Webinfo)<5) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Too short! Please enter your web information.'));
		die($output);
	}
	
	### Attachment Preparation ###
	$file_attached = false; //initially file is not attached
	
	if(isset($_FILES['userFile'])) //check uploaded file
	{
		//get file details we need
		$file_tmp_name 	  = $_FILES['userFile']['tmp_name'];
		$file_name 		  = $_FILES['userFile']['name'];
		$file_size 		  = $_FILES['userFile']['size'];
		$file_type 		  = $_FILES['userFile']['type'];
		$file_error 	  = $_FILES['userFile']['error'];
		
		//exit script and output error if we encounter any
		if($file_error>0)
		{
			$mymsg = array( 
			1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini", 
			2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form", 
			3=>"The uploaded file was only partially uploaded", 
			4=>"No file was uploaded", 
			6=>"Missing a temporary folder" ); 
			
			$output = json_encode(array('type'=>'error', 'text' => $mymsg[$file_error]));
			die($output); 
		}
	
		//read from the uploaded file & base64_encode content for the mail
		$handle = fopen($file_tmp_name, "r");
		$content = fread($handle, $file_size);
		fclose($handle);
		$encoded_content = chunk_split(base64_encode($content));
		
		//now we know we have the file for attachment, set $file_attached to true
		$file_attached = true;
	}

	if($file_attached) //continue if we have the file
	{
		# Mail headers should work with most clients (including thunderbird)
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion()."\r\n";
        $headers .= "From: websiteForm@advisorsacademy.com\r\n";
		$headers .= "Subject:".$subject."\r\n";
        $headers .= "Reply-To: cstubbs@advisorsacademy.com\r\n";
		$headers .= "Content-Type: multipart/mixed; boundary=".md5('boundary1')."\r\n\r\n";
	
		$headers .= "--".md5('boundary1')."\r\n";
		$headers .= "Content-Type: multipart/alternative;  boundary=".md5('boundary2')."\r\n\r\n";
		
		$headers .= "--".md5('boundary2')."\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n\r\n";
		$headers .= $user_Message."\r\n\r\n";
	
		$headers .= "--".md5('boundary2')."--\r\n";
		$headers .= "--".md5('boundary1')."\r\n";
		$headers .= "Content-Type:  ".$file_type."; ";
		$headers .= "name=\"".$file_name."\"\r\n";
		$headers .= "Content-Transfer-Encoding:base64\r\n";
		$headers .= "Content-Disposition:attachment; ";
		$headers .= "filename=\"".$file_name."\"\r\n";
		$headers .= "X-Attachment-Id:".rand(1000,9000)."\r\n\r\n";
		$headers .= $encoded_content."\r\n";
		$headers .= "--".md5('boundary1')."--";	
	}else{
		# Mail headers for plain text mail
		$headers = 'From: websiteForm@advisorsacademy.com' . "\r\n" .
		'Reply-To: advisorsacademy@gmail.com' . "\r\n" . 'Content-Type: text/html; charset=ISO-8859-1\r\n\r\n' . 
		'X-Mailer: PHP/' . phpversion();
	}
	
	//send the mail
	$sentMail = @mail($to_Email, $subject, $user_Message, $headers);
	
	if(!$sentMail) //output success or failure messages
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
		die($output);
	}else{
		$output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_Fname .' Thank you for your email!'));
		die($output);
	}
}
?>