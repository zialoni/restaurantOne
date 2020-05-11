<?php
/**
 * EDIT THE VALUES BELOW THIS LINE TO ADJUST THE CONFIGURATION
 * EACH OPTION HAS A COMMENT ABOVE IT WITH A DESCRIPTION
 */
/**
 * Specify the email address to which all mail messages are sent.
 * The script will try to use PHP's mail() function,
 * so if it is not properly configured it will fail silently (no error).
 */
$mailTo     = 'ziam.loni@gmail.com';

/**
 * Set the message that will be shown on success
 */
$successMsg = 'Thank you! We will contact you ASAP regarding your query.';

/**
 * Set the message that will be shown if not all fields are filled
 */
$fillMsg    = 'Please fill all fields!';

/**
 * Set the message that will be shown on error
 */
$errorMsg   = 'There is a problem, sorry! :(';

/**
 * DO NOT EDIT ANYTHING BELOW THIS LINE, UNLESS YOU'RE SURE WHAT YOU'RE DOING
 */

?>
<?php
if(
    !isset($_POST['name']) ||	
	!isset($_POST['email']) ||
	!isset($_POST['phone']) ||
	!isset($_POST['query']) ||
    empty($_POST['name']) ||
	empty($_POST['email']) ||
	empty($_POST['phone']) ||
	empty($_POST['query']) 
) { //some of the required fields have not been filled out
	
	if( empty($_POST['name']) && empty($_POST['email']) && empty($_POST['phone']) && empty($_POST['query']) ) {
		$json_arr = array( "type" => "error", "msg" => $fillMsg );
		echo json_encode( $json_arr );		
	} 
	else {

		$fields = "";
		if( !isset( $_POST['name'] ) || empty( $_POST['name'] ) ) {
			$fields .= "Name";
		}
		
		if( !isset( $_POST['email'] ) || empty( $_POST['email'] ) ) {
			if( $fields == "" ) {
				$fields .= "Email";
			} else {
				$fields .= ", Email";
			}
		}
		
		if( !isset( $_POST['phone'] ) || empty( $_POST['phone'] ) ) {
			if( $fields == "" ) {
				$fields .= "Phone";
			} else {
				$fields .= ", Phone";
			}
		}	
		
		if( !isset( $_POST['query'] ) || empty( $_POST['query'] ) ) {
			if( $fields == "" ) {
				$fields .= "Query";
			} else {
				$fields .= ", Query";
			}		
		}	
		$json_arr = array( "type" => "error", "msg" => "Please fill ".$fields." fields!" );
		echo json_encode( $json_arr );
		
	}

} 
else { //all required fields have been filled out
	// Validate e-mail
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
		//everything is good, so create and send email
		$msg = "Query \r\n";
		$msg .= "Name: ".$_POST['name']."\r\n";			
		$msg .= "Email: ".$_POST['email']."\r\n";
		$msg .= "Phone: ".$_POST['phone']."\r\n";
		$msg .= "Message: ".$_POST['query']."\r\n";
		
		$success = @mail($mailTo, $_POST['email'], $msg, 'From: ' . $_POST['name'] . '<' . $_POST['email'] . '>');
		
		if ($success) { //email successfully sent
			$json_arr = array( "type" => "success", "msg" => $successMsg );
			echo json_encode( $json_arr );
		} 
		else { //something went wrong sending the email
			$json_arr = array( "type" => "error", "msg" => $errorMsg );
			echo json_encode( $json_arr );
		}
		
	} 
	else { //invalid email address
 		$json_arr = array( "type" => "error", "msg" => "Please enter valid email address!" );
		echo json_encode( $json_arr );	
	}

}