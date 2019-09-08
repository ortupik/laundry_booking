<?php  

session_start();
include(dirname(dirname(dirname(__FILE__))) . '/header.php');
include(dirname(dirname(dirname(__FILE__))) . "/config.php");
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_connection.php");
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_login_check.php");
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_adminprofile.php");
include(dirname(dirname(dirname(__FILE__))) . '/objects/class.phpmailer.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_setting.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_front_first_step.php');
$con = new laundry_db();
$conn = $con->connect();
$settings = new laundry_setting();
$settings->conn = $conn;
$first_step=new laundry_first_step();
$first_step->conn=$conn;


if($settings->get_option('ld_smtp_authetication') == 'true'){
	$mail_SMTPAuth = '1';
	if($settings->get_option('ld_smtp_hostname') == "smtp.gmail.com"){
		$mail_SMTPAuth = 'Yes';
	}
	
}else{
	$mail_SMTPAuth = '0';
	if($settings->get_option('ld_smtp_hostname') == "smtp.gmail.com"){
		$mail_SMTPAuth = 'No';
	}
}

$mail = new laundry_phpmailer();
$mail->Host = $settings->get_option('ld_smtp_hostname');
$mail->Username = $settings->get_option('ld_smtp_username');
$mail->Password = $settings->get_option('ld_smtp_password');
$mail->Port = $settings->get_option('ld_smtp_port');
$mail->SMTPSecure = $settings->get_option('ld_smtp_encryption');
$mail->SMTPAuth = $mail_SMTPAuth;
$mail->CharSet = "UTF-8";

$objlogin = new laundry_login_check();
$objlogin->conn = $conn;
$objadmininfo = new laundry_adminprofile();
$objadmininfo->conn = $conn;
$company_email=$settings->get_option('ld_company_email');$company_name=$settings->get_option('ld_company_name');
if (isset($_POST['checkadmin'])) {
    $name = trim(strip_tags(mysqli_real_escape_string($conn,filter_var($_POST['name'], FILTER_SANITIZE_STRING))));
    $password = md5(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    $objlogin->remember = filter_var($_POST['remember'], FILTER_SANITIZE_STRING);
    $objlogin->cookie_passwords = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $t = $objlogin->checkadmin($name, $password);
} elseif (isset($_POST['logout'])) {
    session_destroy();
} elseif (isset($_GET['resetpassword'])) {
    $newpass = $_GET['password'];
    $id = $_GET['userid'];
    $objlogin->resetpassword($id, $newpass);
} elseif (isset($_POST['action']) && filter_var($_POST['action'], FILTER_SANITIZE_STRING) == 'forget_password') {
    $objadmininfo->email = trim(strip_tags(mysqli_real_escape_string($conn, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL))));
    $res = $objadmininfo->forget_password();
    $userid = $res[0];
    if (count($res) >= 1) {
		$current_time = date('Y-m-d H:i:s');
        $ency_code = base64_encode(base64_encode($userid + 135) . '#' . strtotime("+120 minutes", strtotime($current_time)));
        $to = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $subject = "Forget Password";
        $from = $settings->get_option('ld_company_email');
        $body = '<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Welcome to ' . $settings->get_option('ld_company_name'). '</title>
	</head>
	<body>
		<div style="margin: 0;padding: 0;font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif;font-size: 100%;line-height: 1.6;box-sizing: border-box;">
			<div style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
				<table style="border: 1px solid #c2c2c2;width: 100%;float: left;margin: 30px 0px;-webkit-border-radius: 5px;-moz-border-radius: 5px;-o-border-radius: 5px;border-radius: 5px;">
					<tbody>
						<tr>
							<td>
								<div style="padding: 25px 30px;background: #fff;float: left;width: 90%;display: block;">
									<div style="border-bottom: 1px solid #e6e6e6;float: left;width: 100%;display: block;">
										<h3 style="color: #606060;font-size: 20px;margin: 15px 0px 0px;font-weight: 400;">Hi,</h3><br />
										<p style="color: #606060;font-size: 15px;margin: 10px 0px 15px;">Forgot your password - <a href="' . SITE_URL . 'admin/forgot-password_admin.php?code=' . $ency_code . '" >Reset it here</a></p>
									</div>
									<div style="padding: 15px 0px;float: left;width: 100%;">
										<h5 style="color: #606060;font-size: 13px;margin: 10px 0px px;">Regards,</h5>
										<h6 style="color: #606060;font-size: 14px;font-weight: 600;margin: 10px 0px 15px;">' . $settings->get_option('ld_company_name') . '</h6>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>';
        if($settings->get_option('ld_smtp_hostname') != '' && $settings->get_option('ld_email_sender_name') != '' && $settings->get_option('ld_email_sender_address') != '' && $settings->get_option('ld_smtp_username') != '' && $settings->get_option('ld_smtp_password') != '' && $settings->get_option('ld_smtp_port') != ''){
            $mail->IsSMTP();
        }else{
            $mail->IsMail();
        }
        $mail->SMTPDebug  = 0;
        $mail->IsHTML(true);
        $mail->From = $company_email;
        $mail->FromName = $company_name;
        $mail->Sender = $company_email;
        $mail->AddAddress($to,"Admin");
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
		$mail->ClearAllRecipients();
    } else {
        echo filter_var("not", FILTER_SANITIZE_STRING);
    }
} elseif (isset($_POST['action']) && filter_var($_POST['action'], FILTER_SANITIZE_STRING) == 'reset_new_password') {
    $objadmininfo->id = $_SESSION['user_id'];
    $objadmininfo->password = filter_var($_POST['retype_new_reset_pass'], FILTER_SANITIZE_STRING);
    $reset_new_pass = $objadmininfo->update_password();
    if ($reset_new_pass) {
        echo filter_var("password reset successfully", FILTER_SANITIZE_STRING);
    }
    unset($_SESSION['fp_admin']);
    unset($_SESSION['fp_user']);
}
?>