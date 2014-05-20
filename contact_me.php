<?php
if($_POST)
{
	$to_Email   	= "cstubbs@advisorsacademy.com"; //Replace with recipient email address
	$subject        = 'Ah!! My email from Somebody out there...'; //Subject line for emails
	
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
	$user_Fmame        = filter_var($_POST["userFname"], FILTER_SANITIZE_STRING);
    $user_Lname        = filter_var($_POST["userLname"], FILTER_SANITIZE_STRING);
	$user_Socialmedia  = filter_var($_POST["userSocialMedia"], FILTER_SANITIZE_EMAIL);
	$user_Content      = filter_var($_POST["userContent"], FILTER_SANITIZE_STRING);
	$user_Video        = filter_var($_POST["userVideos"], FILTER_SANITIZE_STRING);
    $user_Webinfo      = filter_var($_POST["userWebinfo"], FILTER_SANITIZE_STRING);
	
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
		$output = json_encode(array('type'=>'error', 'text' => 'Too short! Please enter something.'));
		die($output);
	}
    if(strlen($user_Content)<5) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Too short! Please enter more content.'));
		die($output);
	}
    if(strlen($user_Videos)<5) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Too short! Please enter full dates.'));
		die($output);
	}
    if(strlen($user_Webinfo)<5) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Too short! Please enter something.'));
		die($output);
	}
	
	### Attachment Preparation ###
	$file_attached_one = false; //initially file is not attached
    $file_attached_two = false; //initially file is not attached
	
	if(isset($_FILES['logo'])) //check the logo upload
	{
		//get file details we need
		$file_one_tmp_name 	  = $_FILES['file_attach']['tmp_name'];
		$file_one_name 		  = $_FILES['file_attach']['name'];
		$file_one_size 		  = $_FILES['file_attach']['size'];
		$file_one_type 		  = $_FILES['file_attach']['type'];
		$file_one_error 	  = $_FILES['file_attach']['error'];
		
		//exit script and output error if we encounter any
		if($file_one_error>0)
		{
			$mymsgone = array( 
			1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini", 
			2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form", 
			3=>"The uploaded file was only partially uploaded", 
			4=>"No file was uploaded", 
			6=>"Missing a temporary folder" ); 
			
			$output_one = json_encode(array('type'=>'error', 'text' => $mymsgone[$file_one_error]));
			die($output_one); 
		}
	
		//read from the uploaded file & base64_encode content for the mail
		$handle = fopen($file_one_tmp_name, "r");
		$content = fread($handle, $file_one_size);
		fclose($handle);
		$encoded_content = chunk_split(base64_encode($content));
		
		//now we know we have the file for attachment, set $file_attached to true
		$file_attached_one = true;
	}
    
    if(isset($_FILES['interactive'])) //check the zip of photos upload
	{
		//get file details we need
		$file_two_tmp_name 	  = $_FILES['file_attach']['tmp_name'];
		$file_two_name 		  = $_FILES['file_attach']['name'];
		$file_two_size 		  = $_FILES['file_attach']['size'];
		$file_two_type 		  = $_FILES['file_attach']['type'];
		$file_two_error 	  = $_FILES['file_attach']['error'];
		
		//exit script and output error if we encounter any
		if($file_two_error>0)
		{
			$mymsg_two = array( 
			1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini", 
			2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form", 
			3=>"The uploaded file was only partially uploaded", 
			4=>"No file was uploaded", 
			6=>"Missing a temporary folder" ); 
			
			$output_two = json_encode(array('type'=>'error', 'text' => $mymsg_two[$file_two_error]));
			die($output_two); 
		}
	
		//read from the uploaded file & base64_encode content for the mail
		$handle_two = fopen($file_two_tmp_name, "r");
		$content_two = fread($handle_two, $file_two_size);
		fclose($handle_two);
		$encoded_content_two = chunk_split(base64_encode($content_two));
		
		//now we know we have the file for attachment, set $file_attached to true
		$file_attached_two = true;
	}

	if($file_attached_one || $file_attached_two) //continue if we have the file
	{
		# Mail headers should work with most clients (including thunderbird)
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion()."\r\n";
		//$headers .= "From:".$user_Email."\r\n";
        $headers .= "From:Advisor's Site Order Form\r\n";
		$headers .= "Subject:".$subject."\r\n";
		//$headers .= "Reply-To: ".$user_Email."" . "\r\n";
        $headers .= "Reply-To: cstubbs@advisorsacademy.com\r\n";
		$headers .= "Content-Type: multipart/mixed; boundary=".md5('boundary1')."\r\n\r\n";
	
		$headers .= "--".md5('boundary1')."\r\n";
		$headers .= "Content-Type: multipart/alternative;  boundary=".md5('boundary2')."\r\n\r\n";
		
		$headers .= "--".md5('boundary2')."\r\n";
		$headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n\r\n";
		$headers .= $user_Message."\r\n\r\n";
        
        $user_Message = $user_Fname ." ". $user_Lname ." \r\n" ;
        $user_Message += $user_Socialmedia ."\r\n";
        $user_Message += $user_Content ."\r\n";
        $user_Message += $user_Videos ."\r\n";
        $user_Message += $user_Webinfo ."\r\n\r\n";
	       
		$headers .= "--".md5('boundary2')."--\r\n";
		$headers .= "--".md5('boundary1')."\r\n";
		$headers .= "Content-Type:  ".$file_one_type."; ";
		$headers .= "name=\"".$file_one_name."\"\r\n";
		$headers .= "Content-Transfer-Encoding:base64\r\n";
		$headers .= "Content-Disposition:attachment; ";
		$headers .= "filename=\"".$file_one_name."\"\r\n";
		$headers .= "X-Attachment-Id:".rand(1000,9000)."\r\n\r\n";
		$headers .= $encoded_content."\r\n";
        $headers .= "Content-Type:  ".$file_two_type."; ";
		$headers .= "name=\"".$file_two_name."\"\r\n";
		$headers .= "Content-Transfer-Encoding:base64\r\n";
		$headers .= "Content-Disposition:attachment; ";
		$headers .= "filename=\"".$file_two_name."\"\r\n";
		$headers .= "X-Attachment-Id:".rand(1000,9000)."\r\n\r\n";
		$headers .= $encoded_content_two."\r\n";
		$headers .= "--".md5('boundary1')."--";	
	}else{
		# Mail headers for plain text mail
		//$headers = 'From: '.$user_Email.'' . "\r\n" .
        $headers = 'From: from@advisorsacademy.com' . "\r\n" .
		//'Reply-To: '.$user_Email.'' . "\r\n" .
            'Reply-To: Replyto@advisorsacademy.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	}
	
	//send the mail
	$sentMail = @mail($to_Email, $subject, $user_Message, $headers);
	
	if(!$sentMail) //output success or failure messages
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
		die($output);
	}else{
		$output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_Fname .' Thank you for your email'));
		die($output);
	}
}
?>