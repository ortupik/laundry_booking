<?php 
if(isset($_POST['action']) && filter_var($_POST['action'], FILTER_SANITIZE_STRING) == "uninstall_extension") {
	include(dirname(dirname(dirname(__FILE__))). "/config.php");
	include(dirname(dirname(dirname(__FILE__))). "/objects/class_connection.php");
	include(dirname(dirname(dirname(__FILE__))). '/objects/class_setting.php'); 
	$cvars = new laundry_myvariable();
	$host = trim($cvars->hostnames);
	$un = trim($cvars->username);
	$ps = trim($cvars->passwords); 
	$db = trim($cvars->database);

	$con = new laundry_db();
	$conn = $con->connect();

	$settings = new laundry_setting();
	$settings->conn = $conn;
	
	function delete_files($target) {
		if(is_dir($target)){
			$files = glob( $target . '*', GLOB_MARK );

			foreach( $files as $file )
			{
				delete_files( $file );      
			}
			rmdir( $target );
		} elseif(is_file($target)) {
			unlink( $target );  
		}
	}
	
	$ext_arr = glob(dirname(dirname(dirname(__FILE__))).'/extension/*');
	
	foreach($ext_arr as $ext){
		if('extension/'.filter_var($_POST['extension'], FILTER_SANITIZE_STRING) == $ext){
			delete_files($ext."/");
		}else if(strpos($ext, filter_var($_POST['extension'], FILTER_SANITIZE_STRING)) !== false && strpos($ext, '.zip') !== false){
			unlink($ext);
		}
	}
	$settings->set_option(filter_var($_POST['purchase_option'], FILTER_SANITIZE_STRING),'N');
	$settings->set_option(filter_var($_POST['version_option'], FILTER_SANITIZE_STRING),'');
}
if(isset($_POST['action']) && filter_var($_POST['action'], FILTER_SANITIZE_STRING) == "deactivate_extension") {
	include(dirname(dirname(dirname(__FILE__))). '/objects/class_setting.php'); 
	include(dirname(dirname(dirname(__FILE__))). "/objects/class_connection.php");
	$cvars = new laundry_myvariable();
	$host = trim($cvars->hostnames);
	$un = trim($cvars->username);
	$ps = trim($cvars->passwords); 
	$db = trim($cvars->database);

	$con = new laundry_db();
	$conn = $con->connect();

	$settings = new laundry_setting();
	$settings->conn = $conn;
	
	$settings->set_option(filter_var($_POST['purchase_option'], FILTER_SANITIZE_STRING),'N');
	$settings->set_option(filter_var($_POST['version_option'], FILTER_SANITIZE_STRING),'');
}