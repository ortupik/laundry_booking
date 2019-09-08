<?php  

session_start();
include(dirname(dirname(dirname(__FILE__))).'/config.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_connection.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_sms_template.php');
$database= new laundry_db();
$conn=$database->connect();
$database->conn=$conn;
$sms_template = new laundry_sms_template();
$sms_template->conn = $conn;
if(isset($_POST['save_sms_template'])){
    $sms_template->id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $sms_template->sms_message = base64_encode(filter_var($_POST['sms_messages'], FILTER_SANITIZE_STRING));
    $updated = $sms_template->update_sms_template();
}
else if(isset($_POST['save_sms_template_status'])){
    $sms_template->id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $sms_template->sms_template_status = filter_var($_POST['sms_template_status'], FILTER_SANITIZE_STRING);
    $updated = $sms_template->update_sms_template_status();
}
else if(isset($_POST['default_sms_content'])){
    $sms_template->id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $getdata = $sms_template->get_default_sms_template();
    echo base64_decode($getdata);
}
?>