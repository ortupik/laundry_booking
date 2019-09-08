<?php 

	session_start();
	include(dirname(dirname(dirname(__FILE__))).'/config.php');
	include(dirname(dirname(dirname(__FILE__))).'/objects/class_connection.php');
	include(dirname(dirname(dirname(__FILE__))).'/objects/class_email_template.php');
	
	$database= new laundry_db();
	$conn=$database->connect();
	$database->conn=$conn;
	
	$email_template = new laundry_email_template();
	$email_template->conn = $conn;
	
	if(isset($_POST['save_email_template'])){
		$email_template->id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
		$email_template->email_message = base64_encode(filter_var($_POST['email_message'], FILTER_SANITIZE_STRING));
		$updated = $email_template->update_email_template();
	}
	else if(isset($_POST['save_email_template_status'])){
		$email_template->id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
		$email_template->email_template_status = filter_var($_POST['email_template_status'], FILTER_SANITIZE_STRING);
		$updated = $email_template->update_email_template_status();
	}
    else if(isset($_POST['default_email_content'])){
        $email_template->id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        $getdata = $email_template->get_default_email_template();
        echo base64_decode($getdata);
    }
?>