<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include(dirname(dirname(dirname(__FILE__)))."/config.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_dashboard.php");
include(dirname(dirname(dirname(__FILE__)))."/header.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_setting.php");
include(dirname(dirname(dirname(__FILE__))).'/objects/class_booking.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_general.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class.phpmailer.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_adminprofile.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_front_first_step.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/plivo.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_email_template.php');

include(dirname(dirname(dirname(__FILE__))).'/assets/twilio/Services/Twilio.php');
include(dirname(dirname(dirname(__FILE__)))."/objects/class_nexmo.php");
if ( is_file(dirname(dirname(dirname(__FILE__))).'/extension/GoogleCalendar/google-api-php-client/src/Google_Client.php')) 
{
	require_once dirname(dirname(dirname(__FILE__))).'/extension/GoogleCalendar/google-api-php-client/src/Google_Client.php';
}
include(dirname(dirname(dirname(__FILE__)))."/objects/class_gc_hook.php");

$con = new laundry_db();
$conn = $con->connect();

$nexmo_admin = new laundry_ld_nexmo();
$nexmo_client = new laundry_ld_nexmo();

$first_step=new laundry_first_step();
$first_step->conn=$conn;

$objdashboard = new laundry_dashboard();
$objdashboard->conn = $conn;

$gc_hook = new laundry_gcHook();
$gc_hook->conn = $conn;

$objadminprofile = new laundry_adminprofile();
$objadminprofile->conn = $conn;

$objadmin = new laundry_adminprofile();
$objadmin->conn = $conn;

$objbooking = new laundry_booking();
$objbooking->conn = $conn;

$setting = new laundry_setting();
$setting->conn = $conn;


if($setting->get_option('ld_smtp_authetication') == 'true'){
	$mail_SMTPAuth = '1';
	if($setting->get_option('ld_smtp_hostname') == "smtp.gmail.com"){
		$mail_SMTPAuth = 'Yes';
	}
	
}else{
	$mail_SMTPAuth = '0';
	if($setting->get_option('ld_smtp_hostname') == "smtp.gmail.com"){
		$mail_SMTPAuth = 'No';
	}
}

$mail = new laundry_phpmailer();
$mail->Host = $setting->get_option('ld_smtp_hostname');
$mail->Username = $setting->get_option('ld_smtp_username');
$mail->Password = $setting->get_option('ld_smtp_password');
$mail->Port = $setting->get_option('ld_smtp_port');
$mail->SMTPSecure = $setting->get_option('ld_smtp_encryption');
$mail->SMTPAuth = $mail_SMTPAuth;
$mail->CharSet = "UTF-8";

$mail_a = new laundry_phpmailer();
$mail_a->Host = $setting->get_option('ld_smtp_hostname');
$mail_a->Username = $setting->get_option('ld_smtp_username');
$mail_a->Password = $setting->get_option('ld_smtp_password');
$mail_a->Port = $setting->get_option('ld_smtp_port');
$mail_a->SMTPSecure = $setting->get_option('ld_smtp_encryption');
$mail_a->SMTPAuth = $mail_SMTPAuth;
$mail_a->CharSet = "UTF-8";

$mail_s = new laundry_phpmailer();
$mail_s->Host = $setting->get_option('ld_smtp_hostname');
$mail_s->Username = $setting->get_option('ld_smtp_username');
$mail_s->Password = $setting->get_option('ld_smtp_password');
$mail_s->Port = $setting->get_option('ld_smtp_port');
$mail_s->SMTPSecure = $setting->get_option('ld_smtp_encryption');
$mail_s->SMTPAuth = $mail_SMTPAuth;
$mail_s->CharSet = "UTF-8";

/*NEXMO SMS GATEWAY VARIABLES*/

$nexmo_admin->ld_nexmo_api_key = $setting->get_option('ld_nexmo_api_key');
$nexmo_admin->ld_nexmo_api_secret = $setting->get_option('ld_nexmo_api_secret');
$nexmo_admin->ld_nexmo_from = $setting->get_option('ld_nexmo_from');

$nexmo_client->ld_nexmo_api_key = $setting->get_option('ld_nexmo_api_key');
$nexmo_client->ld_nexmo_api_secret = $setting->get_option('ld_nexmo_api_secret');
$nexmo_client->ld_nexmo_from = $setting->get_option('ld_nexmo_from');

$general=new laundry_general();
$general->conn=$conn;

$symbol_position=$setting->get_option('ld_currency_symbol_position');
$decimal=$setting->get_option('ld_price_format_decimal_places');

$emailtemplate=new laundry_email_template();
$emailtemplate->conn=$conn;

$getcurrency_symbol_position=$setting->get_option('ld_currency_symbol_position');
$getdateformate = $setting->get_option('ld_date_picker_date_format');
$time_format = $setting->get_option('ld_time_format');

$booking = new laundry_booking();
$booking->conn = $conn;
$lang = $setting->get_option("ld_language");
$label_language_values = array();
$language_label_arr = $setting->get_all_labelsbyid($lang);


/*SMS GATEWAY VARIABLES*/
$plivo_sender_number = $setting->get_option('ld_sms_plivo_sender_number');
$twilio_sender_number = $setting->get_option('ld_sms_twilio_sender_number');

/* textlocal gateway variables */
$textlocal_username =$setting->get_option('ld_sms_textlocal_account_username');
$textlocal_hash_id = $setting->get_option('ld_sms_textlocal_account_hash_id');

/*NEED VARIABLE FOR EMAIL*/
$company_city = $setting->get_option('ld_company_city'); $company_state = $setting->get_option('ld_company_state'); $company_zip = $setting->get_option('ld_company_zip_code'); $company_country = $setting->get_option('ld_company_country'); 
$company_phone = strlen($setting->get_option('ld_company_phone')) < 6 ? "" : $setting->get_option('ld_company_phone');
$company_email = $setting->get_option('ld_company_email');$company_address = $setting->get_option('ld_company_address'); 

$admin_phone_twilio = $setting->get_option('ld_sms_twilio_admin_phone_number');
$admin_phone_plivo = $setting->get_option('ld_sms_plivo_admin_phone_number');
/*CHECK FOR VC AND PARKING STATUS END*/

/*  set admin name */
$get_admin_name_result = $objadminprofile->readone_adminname();
$get_admin_name = $get_admin_name_result[3];
if($get_admin_name == ""){
	$get_admin_name = "Admin";
}
$admin_email = $setting->get_option('ld_admin_optional_email');
/* set admin name */
/* set business logo and logo alt */
 if($setting->get_option('ld_company_logo') != null && $setting->get_option('ld_company_logo') != ""){
	$business_logo= SITE_URL.'assets/images/services/'.$setting->get_option('ld_company_logo');
	$business_logo_alt= $setting->get_option('ld_company_name');
}else{
	$business_logo= '';
	$business_logo_alt= $setting->get_option('ld_company_name');
}
/* set business logo and logo alt */
		
if ($language_label_arr[1] != "" || $language_label_arr[3] != "" || $language_label_arr[4] != "" || $language_label_arr[5] != "")
{
	$default_language_arr = $setting->get_all_labelsbyid("en");
	if($language_label_arr[1] != ''){
		$label_decode_front = base64_decode($language_label_arr[1]);
	}else{
		$label_decode_front = base64_decode($default_language_arr[1]);
	}
	if($language_label_arr[3] != ''){
		$label_decode_admin = base64_decode($language_label_arr[3]);
	}else{
		$label_decode_admin = base64_decode($default_language_arr[3]);
	}
	if($language_label_arr[4] != ''){
		$label_decode_error = base64_decode($language_label_arr[4]);
	}else{
		$label_decode_error = base64_decode($default_language_arr[4]);
	}
	if($language_label_arr[5] != ''){
		$label_decode_extra = base64_decode($language_label_arr[5]);
	}else{
		$label_decode_extra = base64_decode($default_language_arr[5]);
	}
	
	$label_decode_front_unserial = unserialize($label_decode_front);
	$label_decode_admin_unserial = unserialize($label_decode_admin);
	$label_decode_error_unserial = unserialize($label_decode_error);
	$label_decode_extra_unserial = unserialize($label_decode_extra);
    
	$label_language_arr = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial);
	
	foreach($label_language_arr as $key => $value){
		$label_language_values[$key] = urldecode($value);
	}
}
else
{
    $default_language_arr = $setting->get_all_labelsbyid("en");
    
	$label_decode_front = base64_decode($default_language_arr[1]);
	$label_decode_admin = base64_decode($default_language_arr[3]);
	$label_decode_error = base64_decode($default_language_arr[4]);
	$label_decode_extra = base64_decode($default_language_arr[5]);	
	
	$label_decode_front_unserial = unserialize($label_decode_front);
	$label_decode_admin_unserial = unserialize($label_decode_admin);
	$label_decode_error_unserial = unserialize($label_decode_error);
	$label_decode_extra_unserial = unserialize($label_decode_extra);
    
	$label_language_arr = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial);
	
	foreach($label_language_arr as $key => $value){
		$label_language_values[$key] = urldecode($value);
	}
}

/*new file include*/
include(dirname(dirname(dirname(__FILE__))).'/assets/lib/date_translate_array.php');
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
if(isset($_POST['getcleintdetailwith_updatereadstatus'])){
	/*new file include*/
	include(dirname(dirname(dirname(__FILE__))).'/assets/lib/date_translate_array.php');
    $orderdetail = $objdashboard->getclientorder(filter_var($_POST['orderid'], FILTER_SANITIZE_STRING));
    $objdashboard->update_read_status(filter_var($_POST['orderid'], FILTER_SANITIZE_STRING));
    ?>
    <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close closesss" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><?php echo filter_var($label_language_values['booking_details'], FILTER_SANITIZE_STRING);?></h4>
                </div>
                <div class="modal-body mb-20">
                    <ul class="list-unstyled ld-cal-booking-details mypopupul">
                        <li>
                            <label style="width: 120px; margin-right: 0;"><?php echo filter_var($label_language_values['booking_status'], FILTER_SANITIZE_STRING);?> : </label>
                            <div class="ld-booking-status">
                                <?php 
																$reject_reason = $orderdetail[7];
                                if($orderdetail[6]=='A')
                                {
                                    $booking_stats=$label_language_values['active'];
                                }
                                elseif($orderdetail[6]=='C')
                                {
                                    $booking_stats='<i class="fa fa-check txt-success">'.$label_language_values['confirmed'].'</i>';
                                }
                                elseif($orderdetail[6]=='R')
                                {
                                    $booking_stats='<i class="fa fa-ban txt-danger">'.$label_language_values['rejected'].'</i>';
                                }
                                elseif($orderdetail[6]=='RS')
                                {
                                    $booking_stats='<i class="fa fa-pencil-square-o txt-info">'.$label_language_values['rescheduled'].'</i>';
                                }
                                elseif($orderdetail[6]=='CC')
                                {
                                    $booking_stats='<i class="fa fa-times txt-primary">'.$label_language_values['cancelled_by_client'].'</i>';
                                }
                                elseif($orderdetail[6]=='CS')
                                {
                                    $booking_stats='<i class="fa fa-times-circle-o txt-info">'.$label_language_values['cancelled_by_service_provider'].'</i>';
                                }
                                elseif($orderdetail[6]=='CO')
                                {
                                    $booking_stats='<i class="fa fa-thumbs-o-up txt-success">'.$label_language_values['appointment_completed'].'</i>';
                                }
                                else
                                {
                                    $booking_stats='<i class="fa fa-thumbs-o-down txt-danger">'.$label_language_values['appointment_marked_as_no_show'].'</i>';
                                }
                                echo $booking_stats;
                                ?>
                            </div>
                        </li>
												<?php   if($setting->get_option('ld_show_self_service') == "E") { ?>
												<li>
                            <label><?php echo filter_var($label_language_values['self_service'], FILTER_SANITIZE_STRING);?></label>
                            <span class="self-service-html span-scroll">: 
														<?php
															$self_service = "No";
															if($orderdetail[12] == "Y")
															{
																$self_service = "Yes";
															}
															echo filter_var($self_service, FILTER_SANITIZE_STRING);
														?></span>
                        </li>
												<?php } ?>
                        <li class="">
														<label><?php echo filter_var($label_language_values['pickup'], FILTER_SANITIZE_STRING);?></label>
                            <span><i class="fa fa-calendar"></i><?php echo str_replace($english_date_array,$selected_lang_label,date($getdateformate, strtotime($orderdetail[0])));?>  <i class="fa fa-clock-o ml-10 mr-1"></i>
							<?php 
								if($time_format == 12){
								?>
								<?php  echo str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($orderdetail[0])))." to ".str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($orderdetail[8])));?></span>
								<?php 
								}else{
								?>
								<?php  echo date("H:i", strtotime($orderdetail[0]))." to ".date("H:i", strtotime($orderdetail[8]));?></span>
								<?php 
								}
								?>
								</li>
								<li>
								<?php   
								$order_id = filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);
								$objbooking->order_id = $order_id;
								$book_unit_detail = $objbooking->get_booking_units_from_order($order_id);
								$units = "";
								if($book_unit_detail->num_rows > 0)
								{
									$units_array = array();
									while($unit_row = mysqli_fetch_assoc($book_unit_detail))
									{
										$units_array[] = $unit_row["unit_name"]." - ".$unit_row["unit_qty"];
									}
									$units = implode(", ",$units_array);
								}
								?>
                            </span>
								<?php
								if($orderdetail[9] == "E"){									
								?>	
										<label><?php echo filter_var($label_language_values['delivery'], FILTER_SANITIZE_STRING);?></label>
										<span><i class="fa fa-calendar"></i><?php echo str_replace($english_date_array,$selected_lang_label,date($getdateformate, strtotime($orderdetail[10])));?>  <i class="fa fa-clock-o ml-10 mr-1"></i>
							<?php 
								if($time_format == 12){
								?>
								<?php  echo str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($orderdetail[10])))." to ".str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($orderdetail[11])));?></span>
								<?php 
								}else{
								?>
								<?php  echo date("H:i", strtotime($orderdetail[10]))." to ".date("H:i", strtotime($orderdetail[11]));?></span>
								<?php 
								}
								}
								$order_id = filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);
								$objbooking->order_id = $order_id;
								$book_unit_detail = $objbooking->get_booking_units_from_order($order_id);
								$units = "";
								if($book_unit_detail->num_rows > 0)
								{
									$units_array = array();
									while($unit_row = mysqli_fetch_assoc($book_unit_detail))
									{
										$units_array[] = $unit_row["unit_name"]." - ".$unit_row["unit_qty"];
									}
									$units = implode(", ",$units_array);
								}
								?>
                            </span>
                        </li>

                        <li>
                            <label><?php echo filter_var($label_language_values['service'], FILTER_SANITIZE_STRING);?></label>
                            <span class="service-html span-scroll">: <?php  echo filter_var($orderdetail[1], FILTER_SANITIZE_STRING);?></span>
                        </li>
                        <li>
                            <label><?php echo filter_var($label_language_values['units'], FILTER_SANITIZE_STRING);?></label>
                            <span class="calendar_providername span-scroll">: <?php  echo filter_var($units, FILTER_SANITIZE_STRING);?></span>
                        </li>

                        <li>
                            <label><?php echo filter_var($label_language_values['price'], FILTER_SANITIZE_STRING);?></label>
                            <span class="span-scroll">: <?php  echo filter_var($general->ld_price_format($orderdetail[2],$symbol_position,$decimal), FILTER_SANITIZE_STRING);
                               ?> </span>
                        </li>
                        <li><h6 class="ld-customer-details-hr"><?php echo filter_var($label_language_values['customer'], FILTER_SANITIZE_STRING);?></h6>
                        </li>
                        <?php 
                        if($orderdetail[3]==0)
                        {
                            $gc  = $objdashboard->getguestclient($orderdetail[4]);
                            ?>
                            <li>
                                <label><?php echo filter_var($label_language_values['name'], FILTER_SANITIZE_STRING);?></label>
                                <span class="client_name span-scroll">: <?php  echo filter_var($gc[2], FILTER_SANITIZE_STRING);?></span>
                            </li>
                            <li>
                                <label><?php echo filter_var($label_language_values['email'], FILTER_SANITIZE_STRING);?></label>
                                <span class="client_email span-scroll">: <?php  echo filter_var($gc[3], FILTER_SANITIZE_STRING);?></span>
                            </li>
                            <li>
                                <label><?php echo filter_var($label_language_values['phone'], FILTER_SANITIZE_STRING);?></label>
                                <span class="client_phone span-scroll">: <?php  echo filter_var($gc[4], FILTER_SANITIZE_STRING);?></span>
                            </li>
                            <li>
                                <label><?php echo filter_var($label_language_values['payment'], FILTER_SANITIZE_STRING);?></label>
                                <span class="client_payment span-scroll">: <?php  echo filter_var($orderdetail[5], FILTER_SANITIZE_STRING);?></span>
                            </li>
                            <?php 
							$temppp= unserialize(base64_decode($gc[5]));
							$temp = str_replace('\\','',$temppp);
							
                            ?>

                            <?php 
                                if($temp['notes']!=""){
                                    ?>
                                    <li>
                                        <label><?php echo filter_var($label_language_values['notes'], FILTER_SANITIZE_STRING);?></label>
                                        <span class="notes span-scroll">: <?php  echo filter_var($temp['notes'], FILTER_SANITIZE_STRING);?></span>
                                    </li>
                                <?php    
                                }
								if($reject_reason != ""){
									?>
                                    <li>
                                        <label><?php echo filter_var($label_language_values['reason'], FILTER_SANITIZE_STRING);?></label>
                                        <span class="reason span-scroll">: <?php  echo filter_var($reject_reason, FILTER_SANITIZE_STRING);?></span>
                                    </li>
									<?php    
								}
								if($setting->get_option("ld_company_willwe_getin_status") == "Y") { ?>
                            <li>
                                <label><?php echo filter_var($label_language_values['contact_status'], FILTER_SANITIZE_STRING);?></label>
                                <span class="notes span-scroll">: <?php  echo filter_var($temp['contact_status'], FILTER_SANITIZE_STRING);?></span>
                            </li>
							<?php  
							}
                        }
                        else
                        {
                            $c  = $objdashboard->getguestclient($orderdetail[4]);
							$client_name = explode(" ",$c[2]);
							$cnamess = array_filter($client_name);
							$ccnames = array_values($cnamess);
							if(sizeof($ccnames)>0){
								$client_first_name =  $ccnames[0]; 
								if(isset($ccnames[1])){
									$client_last_name =  $ccnames[1]; 
								}else{
									$client_last_name =  ''; 
								}
							}else{
								$client_first_name =  ''; 
								$client_last_name =  ''; 
							}
							?>
							<?php  if($client_first_name !="" || $client_last_name !=""){ ?>
                            <li>
                                <label><?php echo filter_var($label_language_values['name'], FILTER_SANITIZE_STRING);?></label>
								
                                <span class="client_name span-scroll">: <?php  if($client_first_name !=""){ echo filter_var($client_first_name, FILTER_SANITIZE_STRING) ." " ; }  if($client_last_name !=""){ echo FILTER_VAR($client_last_name, FILTER_SANITIZE_STRING) ; } ?></span>
                            </li>
							<?php  } ?>
							<li>
                                <label><?php echo filter_var($label_language_values['email'], FILTER_SANITIZE_STRING);?></label>
                                <span class="client_email span-scroll">: <?php  echo filter_var($c[3], FILTER_SANITIZE_STRING);?></span>
                            </li>
							
						<?php 
							$fetch_phone =  strlen($c[4]);
							if($fetch_phone >= 6){
						?>
                            <li>
                                <label><?php echo filter_var($label_language_values['phone'], FILTER_SANITIZE_STRING);?></label>
                                <span class="client_phone span-scroll">: <?php  echo filter_var($c[4], FILTER_SANITIZE_STRING);?></span>
                            </li>
							<?php  }
							$payment_status = strtolower($orderdetail[5]);
							if($payment_status == "pay at venue"){
								$payment_status = ucwords($label_language_values['pay_locally']);
							}else{
								$payment_status = ucwords($payment_status);
							}
							?>
							 <li>
                                <label><?php echo filter_var($label_language_values['payment'], FILTER_SANITIZE_STRING);?></label>
                                <span class="client_payment span-scroll">: <?php  echo filter_var($payment_status, FILTER_SANITIZE_STRING);?></span>
                            </li>
                            <?php 
							$temppp= unserialize(base64_decode($c[5]));
							$temp = str_replace('\\','',$temppp);
                            ?>
				<?php  if($temp['address']!="" || $temp['city']!="" || $temp['zip']!="" || $temp['state']!=""  ){ ?>			
							<li>
                                <label><?php echo filter_var($label_language_values['address'], FILTER_SANITIZE_STRING);?></label>
                                <span class="client_address span-scroll">: 
										<?php  if($temp['address']!=""){ echo filter_var($temp['address'], FILTER_SANITIZE_STRING).", " ; } ?> <?php  if($temp['city']!=""){ echo filter_var($temp['city'], FILTER_SANITIZE_STRING).", " ; } ?> <?php  if($temp['zip']!=""){ echo filter_var($temp['zip'], FILTER_SANITIZE_STRING).", " ; } ?><?php if($temp['state']!=""){ echo filter_var($temp['state'], FILTER_SANITIZE_STRING) ; } ?>
								</span>	
                            </li>
							
				<?php  } ?>	  
                            <?php 
                            if($temp['notes']!=""){
                                ?>
                                <li>
                                    <label><?php echo filter_var($label_language_values['notes'], FILTER_SANITIZE_STRING);?></label>
                                    <span class="notes span-scroll">: <?php  echo filter_var($temp['notes'], FILTER_SANITIZE_STRING);?></span>
                                </li>
                            <?php 
                            }
							if($reject_reason != ""){
								?>
								<li>
									<label><?php echo filter_var($label_language_values['reason'], FILTER_SANITIZE_STRING);?></label>
									<span class="reason span-scroll">: <?php  echo filter_var($reject_reason, FILTER_SANITIZE_STRING);?></span>
								</li>
								<?php    
							}
							if($setting->get_option("ld_company_willwe_getin_status") == "Y") { ?>
                            <li>
                                <label><?php echo filter_var($label_language_values['contact_status'], FILTER_SANITIZE_STRING);?></label>

                                <span class="notes span-scroll">: <?php  echo filter_var($temp['contact_status'], FILTER_SANITIZE_STRING);?></span>
                            </li>
                        <?php 
							}
                        }
                        ?>
						<hr>
						<li>
							<label class="assign-app-staff"><?php echo filter_var($label_language_values['assign_appointment_to_staff'], FILTER_SANITIZE_STRING);?></label>
							<span class="span-scroll-staff">
								<?php 
								$get_staff_services = $objadmin->readall_staff_booking();
								$booking->order_id = filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);
								$get_staff_assignid = explode(",",$booking->fetch_staff_of_booking());
								
								$staff_html = "";
								$staff_html .= "<select id='staff_select' class='selectpicker col-md-10' data-live-search='true' multiple data-actions-box='true' data-orderid='".filter_var($_POST['orderid'], FILTER_SANITIZE_STRING)."'>";
								
								$booking->booking_pickup_date_time_start = $orderdetail[0];
								$staff_status = $booking->booked_staff_status();
								$staff_status_arr = explode(",",$staff_status);
								
								foreach($get_staff_services as $staff_details)
								{
									$i = "no";
									$staffname = $staff_details['fullname'];
									$staffid = $staff_details['id'];
									$s_s = "";
									if(in_array($staffid,$staff_status_arr)){
										$s_s = "fa fa-calendar-check-o";
									}
									if(in_array($staffid,$get_staff_assignid)){
										$i = "yes";
									}
									if($i == "yes")
									{
										$staff_html .= "<option selected='selected' data-icon='".$s_s." booking-staff-assigned' value='$staffid'>$staffname</option>";
									}
									else{
										$staff_html .= "<option data-icon='".$s_s." booking-staff-assigned' value='$staffid'>$staffname</option>";
									}
								}

								$staff_html .= "</select><a data-orderid='".filter_var($_POST['orderid'], FILTER_SANITIZE_STRING)."' class='save_staff_booking edit_staff btn btn-info'><i class='remove_add_fafa_class fa fa-pencil-square-o'></i></a>";
								echo $staff_html;
								?>
							</span>
						</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <div class="lda-col12 ld-footer-popup-btn text-center">
						<div class="fln-mrat-dib ">
                        <?php   
                        $booking_day = date("Y-m-d", strtotime($orderdetail[0]));
                        $past_day = "no";
                        $current_day = date("Y-m-d"); 

                        if ($current_day > $booking_day)
                        {
                            $past_day = "yes";
                        }
                        else
                        {
                            $past_day = "no";
                        }
                        if($orderdetail[6]=='C' || $orderdetail[6]=='R' || $orderdetail[6]=='CC' || $past_day == "yes"){
                            ?>
                            <span class="col-xs-4 pr-70 ld-w-32">
                                <a data-id="<?php echo filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);?>" class="btn btn-link confirm_book ld-small-btn ld-complete-appointment" title="<?php echo filter_var($label_language_values['complete_appointment'], FILTER_SANITIZE_STRING);?>"><i class="fa fa-thumbs-up fa-2x"></i><br /><?php echo filter_var($label_language_values['complete'], FILTER_SANITIZE_STRING);?></a>
                            </span>
                                <?php 
                        }
                        else{?>
							
								<span class="col-xs-4 np ld-w-32">
									<a data-id="<?php echo filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);?>" class="btn btn-link ld-small-btn ld-confirm-appointment" title="<?php echo filter_var($label_language_values['confirm_appointment'], FILTER_SANITIZE_STRING);?>"><i class="fa fa-check fa-2x"></i><br /><?php echo filter_var($label_language_values['confirm'], FILTER_SANITIZE_STRING);?></a>
								</span>
								<span class="col-xs-4 np ld-w-32">
									<a data-id="<?php echo filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);?>" id="ld-reject-appointment-cal-popup" class="btn btn-link ld-small-btn book_rejectss" rel="popover" data-placement='top' title="<?php echo filter_var($label_language_values['reject_reason'], FILTER_SANITIZE_STRING);?>"><i class="fa fa-thumbs-o-down fa-2x"></i><br /><?php echo filter_var($label_language_values['reject'], FILTER_SANITIZE_STRING);?></a>

									<div id="popover-reject-appointment-cal-popupss<?php  echo filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);?>" style="display: none;">
										<div class="arrow"></div>
										<table class="form-horizontal" cellspacing="0">
											<tbody>
											<tr>
												<td><textarea class="form-control" id="reason_reject<?php  echo filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);?>" name="" placeholder="<?php echo filter_var($label_language_values['appointment_reject_reason'], FILTER_SANITIZE_STRING);?>" required="required" ></textarea></td>
											</tr>
											<tr>
												<td>
													<button data-id="<?php echo filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);?>" id="" value="Delete" class="btn btn-danger btn-sm reject_bookings" type="submit"><?php echo filter_var($label_language_values['reject'], FILTER_SANITIZE_STRING);?></button>
													<button id="ld-close-reject-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo filter_var($label_language_values['cancel'], FILTER_SANITIZE_STRING);?></button>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</span>
						   <?php   }
							?>

							<span class="col-xs-4 np ld-w-32">
								<a data-id="<?php echo filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);?>" id="ld-delete-appointment-cal-popup" class="ld-delete-appointment-cal-popup btn btn-link ld-small-btn booking_deletess" rel="popover" data-placement='top' title="<?php echo filter_var($label_language_values['delete_this_appointment'], FILTER_SANITIZE_STRING);?>"><i class="fa fa-trash-o fa-2x"></i><br /> <?php  echo filter_var($label_language_values['delete'], FILTER_SANITIZE_STRING);?></a>
							</span>
							<div id="popover-delete-appointment-cal-popupss<?php  echo filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);?>" style="display: none;">
								<div class="arrow"></div>
								<table class="form-horizontal" cellspacing="0">
									<tbody>
									<tr>
										<td>
											<button id="" data-id="<?php echo filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);?>" value="Delete" class="btn btn-danger btn-sm mybtndelete_booking" type="submit"><?php echo filter_var($label_language_values['delete'], FILTER_SANITIZE_STRING);?></button>
											<button id="ld-close-del-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo filter_var($label_language_values['cancel'], FILTER_SANITIZE_STRING);?></button>
										</td>
									</tr>
									</tbody>
								</table>
							</div>
							
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
}
elseif(isset($_POST['complete_booking'])){
	$t_zone_value = $setting->get_option('ld_timezone');
	$server_timezone = date_default_timezone_get();
	if(isset($t_zone_value) && $t_zone_value!=''){
		$offset= $first_step->get_timezone_offset($server_timezone,$t_zone_value);
		$timezonediff = $offset/3600;  
	}else{
		$timezonediff =0;
	}
	
	if(is_numeric(strpos($timezonediff,'-'))){
		$timediffmis = str_replace('-','',$timezonediff)*60;
		$currDateTime_withTZ= strtotime("-".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
	}else{
		$timediffmis = str_replace('+','',$timezonediff)*60;
		$currDateTime_withTZ = strtotime("+".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
	}
	
	$lastmodify = date('Y-m-d H:i:s',$currDateTime_withTZ);
	$order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_STRING);
	
	$objbooking->order_id = $order_id;
	$objbooking->lastmodify = $lastmodify;
	$objbooking->booking_status = "CO";
	
	$objbooking->complete_booking();
}
elseif(isset($_POST['confirm_booking'])){
    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING); /*here id is order id*/
    $orderdetail = $objdashboard->getclientorder($id);
    $lastmodify = date('Y-m-d H:i:s');
    /* Update Confirm status in bookings */
    $objdashboard->confirm_bookings($id,$lastmodify);

    $clientdetail = $objdashboard->clientemailsender($id);

   
	$admin_company_name = $setting->get_option('ld_company_name');
	$setting_date_format = $setting->get_option('ld_date_picker_date_format');
	$setting_time_format = $setting->get_option('ld_time_format');
	$booking_date = str_replace($english_date_array,$selected_lang_label,date($setting_date_format,strtotime($clientdetail['booking_pickup_date_time_start'])));
	if($setting_time_format == 12){
		$booking_time = str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($clientdetail['booking_pickup_date_time_start'])));
	}
	else{
		$booking_time = date("H:i",strtotime($clientdetail['booking_pickup_date_time_start']));
	}
    $company_name = $setting->get_option('ld_email_sender_name');
    $company_email = $setting->get_option('ld_email_sender_address');
    $service_name = $clientdetail['title'];
    if($admin_email == ""){
		$admin_email = $clientdetail['email'];	
	}
    
    
    $price=$general->ld_price_format($orderdetail[2],$symbol_position,$decimal);

    /* units */
    $units = $label_language_values['none'];
    
		$objbooking->order_id = $order_id;
		$book_unit_detail = $objbooking->get_booking_units_from_order($order_id);
		if($book_unit_detail->num_rows > 0)
		{
			$units_array = array();
			while($unit_row = mysqli_fetch_assoc($book_unit_detail))
			{
				$units_array[] = $unit_row["unit_name"]." - ".$unit_row["unit_qty"];
			}
			$units = implode(", ",$units_array);
		}


    /* if this is guest user than */
    if($orderdetail[3]==0)
    {
        $gc  = $objdashboard->getguestclient($orderdetail[4]);
        $temppp= unserialize(base64_decode($gc[5]));
        $temp = str_replace('\\','',$temppp);

        $client_name=$gc[2];
        $client_email=$gc[3];
        $phone_length = strlen($gc[4]);
			
			if($phone_length > 6){
				$client_phone = $gc[4];
			}else{
				$client_phone = "N/A";
			}
			
		
		$firstname=$client_name;
        $lastname='';
        $booking_status=$orderdetail[6];
        $payment_status=$orderdetail[5];
        $client_address=$temp['address'];
        $client_notes=$temp['notes'];
        $client_status=$temp['contact_status'];				
		$client_city = $temp['city'];		$client_state = $temp['state'];		$client_zip	= $temp['zip'];
    }
    else
        /*Registered user */
    {
        $c  = $objdashboard->getguestclient($orderdetail[4]);
        $temppp= unserialize(base64_decode($c[5]));
        $temp = str_replace('\\','',$temppp);
        
        $client_name=$c[2];
        $client_email=$c[3];
		
		 $phone_length = strlen($c[4]);
			
			if($phone_length > 6){
				$client_phone = $c[4];
			}else{
				$client_phone = "N/A";
			}
			
			
			
			$client_name_value="";
			$client_first_name="";
			$client_last_name="";
			
			$client_name_value= explode(" ",$client_name);
			$client_first_name = $client_name_value[0];
			$client_last_name =	$client_name_value[1];
	
					if($client_first_name=="" && $client_last_name==""){
						$firstname = "User";
						$lastname = "";
						$client_name = $firstname.' '.$lastname;
					}elseif($client_first_name!="" && $client_last_name!=""){
						$firstname = $client_first_name;
						$lastname = $client_last_name;
						$client_name = $firstname.' '.$lastname;
					}elseif($client_first_name!=""){
						$firstname = $client_first_name;
						$lastname = "";
						$client_name = $firstname.' '.$lastname;
					}elseif($client_last_name!=""){
						$firstname = "";
						$lastname = $client_last_name;
						$client_name = $firstname.' '.$lastname;
					}
	
			$client_notes = $temp['notes'];	
					if($client_notes==""){
						$client_notes = "N/A";
					}		
			
			$client_status = $temp['contact_status'];	
					if($client_status==""){
						$client_status = "N/A";
					}	
		
		
        $payment_status=$orderdetail[5];
        $client_address=$temp['address'];
		$client_city = $temp['city'];
		$client_state = $temp['state'];	
		$client_zip	= $temp['zip'];
    }
		$payment_status = strtolower($payment_status);
		if($payment_status == "pay at venue"){
			$payment_status = ucwords($label_language_values['pay_locally']);
		}else{
			$payment_status = ucwords($payment_status);
		}
   $searcharray = array('{{service_name}}','{{booking_date}}','{{business_logo}}','{{business_logo_alt}}','{{client_name}}','{{units}}','{{client_email}}','{{phone}}','{{payment_method}}','{{notes}}','{{contact_status}}','{{address}}','{{price}}','{{admin_name}}','{{firstname}}','{{lastname}}','{{app_remain_time}}','{{reject_status}}','{{company_name}}','{{booking_time}}','{{client_city}}','{{client_state}}','{{client_zip}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_address}}','{{admin_name}}');
		
	$replacearray = array($service_name, $booking_date , $business_logo, $business_logo_alt, $client_name, $units, $client_email, $client_phone, $payment_status, $client_notes, $client_status,$client_address,$price,$get_admin_name,$firstname,$lastname,'','',$admin_company_name,$booking_time,$client_city,$client_state,$client_zip,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_address,$get_admin_name);
		
    $emailtemplate->email_subject="Appointment Approved";
    $emailtemplate->user_type="C";
    $clientemailtemplate=$emailtemplate->readone_client_email_template_body();

    if($clientemailtemplate[2] != ''){
        $clienttemplate = base64_decode($clientemailtemplate[2]);
    }else{
        $clienttemplate = base64_decode($clientemailtemplate[3]);
    }
	$subject=$label_language_values[strtolower(str_replace(" ","_",$clientemailtemplate[1]))];
   
    if($setting->get_option('ld_client_email_notification_status') == 'Y' && $clientemailtemplate[4]=='E' ){
        $client_email_body = str_replace($searcharray,$replacearray,$clienttemplate);
        if($setting->get_option('ld_smtp_hostname') != '' && $setting->get_option('ld_email_sender_name') != '' && $setting->get_option('ld_email_sender_address') != '' && $setting->get_option('ld_smtp_username') != '' && $setting->get_option('ld_smtp_password') != '' && $setting->get_option('ld_smtp_port') != ''){
            $mail->IsSMTP();
        }else{
            $mail->IsMail();
        }
        $mail->SMTPDebug  = 0;
        $mail->IsHTML(true);
        $mail->From = $company_email;
        $mail->FromName = $company_name;
        $mail->Sender = $company_email;
        $mail->AddAddress($client_email, $client_name);
        $mail->Subject = $subject;
        $mail->Body = $client_email_body;
        $mail->send();
		$mail->ClearAllRecipients();
    }
    /*** Email Code End ***/

    /*** Email Code Start ***/
    $emailtemplate->email_subject="Appointment Approved";
    $emailtemplate->user_type="A";
    $adminemailtemplate=$emailtemplate->readone_client_email_template_body();

    if($adminemailtemplate[2] != ''){
        $admintemplate = base64_decode($adminemailtemplate[2]);
    }else{
        $admintemplate = base64_decode($adminemailtemplate[3]);
    }
	$adminsubject=$label_language_values[strtolower(str_replace(" ","_",$adminemailtemplate[1]))];

    if($setting->get_option('ld_admin_email_notification_status')=='Y' && $adminemailtemplate[4]=='E'){
        $admin_email_body = str_replace($searcharray,$replacearray,$admintemplate);

        if($setting->get_option('ld_smtp_hostname') != '' && $setting->get_option('ld_email_sender_name') != '' && $setting->get_option('ld_email_sender_address') != '' && $setting->get_option('ld_smtp_username') != '' && $setting->get_option('ld_smtp_password') != '' && $setting->get_option('ld_smtp_port') != ''){
            $mail_a->IsSMTP();
        }else{
            $mail_a->IsMail();
        }
        $mail_a->SMTPDebug  = 0;
        $mail_a->IsHTML(true);
        $mail_a->From = $company_email;
        $mail_a->FromName = $company_name;
        $mail_a->Sender = $company_email;
        $mail_a->AddAddress($admin_email, $get_admin_name);
        $mail_a->Subject = $adminsubject;
        $mail_a->Body = $admin_email_body;
        $mail_a->send();
				$mail_a->ClearAllRecipients();
    }
   
	$staff_ids = $booking->get_staff_ids_from_bookings($id);
	if($staff_ids != ''){
		$staff_idss = explode(',',$staff_ids);
		if(sizeof($staff_idss) > 0){
			foreach($staff_idss as $sid){
				$staffdetails = $booking->get_staff_detail_for_email($sid);
				$staff_name = $staffdetails['fullname'];
				$staff_email = $staffdetails['email'];		
				$staff_phone = $staffdetails['phone'];		
						
				$staff_searcharray = array('{{service_name}}','{{booking_date}}','{{business_logo}}','{{business_logo_alt}}','{{client_name}}','{{units}}','{{client_email}}','{{phone}}','{{payment_method}}','{{notes}}','{{contact_status}}','{{address}}','{{price}}','{{admin_name}}','{{firstname}}','{{lastname}}','{{app_remain_time}}','{{reject_status}}','{{company_name}}','{{booking_time}}','{{client_city}}','{{client_state}}','{{client_zip}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_address}}','{{admin_name}}','{{staff_name}}','{{staff_email}}');
					
				$staff_replacearray = array($service_name, $booking_date , $business_logo, $business_logo_alt, $client_name, $units,$client_email, $client_phone, $payment_status, $client_notes, $client_status,$client_address,$price,$get_admin_name,$firstname,$lastname,'','',$admin_company_name,$booking_time,$client_city,$client_state,$client_zip,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_address,$get_admin_name,$staff_name,$staff_email);
				
				$emailtemplate->email_subject="Appointment Approved";
				$emailtemplate->user_type="S";
				$staffemailtemplate=$emailtemplate->readone_client_email_template_body();
				
				if($staffemailtemplate[2] != ''){
					$stafftemplate = base64_decode($staffemailtemplate[2]);
				}else{
					$stafftemplate = base64_decode($staffemailtemplate[3]);
				}
				$subject=$label_language_values[strtolower(str_replace(" ","_",$staffemailtemplate[1]))];
			   
				if($setting->get_option('ld_staff_email_notification_status') == 'Y' && $staffemailtemplate[4]=='E' ){
					$client_email_body = str_replace($staff_searcharray,$staff_replacearray,$stafftemplate);
					if($setting->get_option('ld_smtp_hostname') != '' && $setting->get_option('ld_email_sender_name') != '' && $setting->get_option('ld_email_sender_address') != '' && $setting->get_option('ld_smtp_username') != '' && $setting->get_option('ld_smtp_password') != '' && $setting->get_option('ld_smtp_port') != ''){
						$mail_s->IsSMTP();
					}else{
						$mail_s->IsMail();
					}
					$mail_s->SMTPDebug  = 0;
					$mail_s->IsHTML(true);
					$mail_s->From = $company_email;
					$mail_s->FromName = $company_name;
					$mail_s->Sender = $company_email;
					$mail_s->AddAddress($staff_email, $staff_name);
					$mail_s->Subject = $subject;
					$mail_s->Body = $client_email_body;
					$mail_s->send();
					$mail_s->ClearAllRecipients();
				}
				
				/* TEXTLOCAL CODE */
				if($setting->get_option('ld_sms_textlocal_status') == "Y")
				{
					if($setting->get_option('ld_sms_textlocal_send_sms_to_staff_status') == "Y"){
						if(isset($staff_phone) && !empty($staff_phone))
						{
							$template = $objdashboard->gettemplate_sms("C",'S');
							$phone = $staff_phone;				
							if($template[4] == "E") {
								if($template[2] == ""){
									$message = base64_decode($template[3]);
								}
								else{
									$message = base64_decode($template[2]);
								}
							}
							$message = str_replace($staff_searcharray,$staff_replacearray,$message);
							$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
							
							$ch = curl_init('http://api.textlocal.in/send/?');
							curl_setopt($ch, CURLOPT_POST, true);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$result = curl_exec($ch);
							curl_close($ch);
						}
					}
				}
				/*PLIVO CODE*/
				if($setting->get_option('ld_sms_plivo_status')=="Y"){
					if($setting->get_option('ld_sms_plivo_send_sms_to_staff_status') == "Y"){
						if(isset($staff_phone) && !empty($staff_phone))
						{	
							$auth_id = $setting->get_option('ld_sms_plivo_account_SID');
							$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
							$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');

							$template = $objdashboard->gettemplate_sms("C",'S');
							$phone = $staff_phone;
							if($template[4] == "E"){
								if($template[2] == ""){
										$message = base64_decode($template[3]);
								}
								else{
										$message = base64_decode($template[2]);
								}
								$client_sms_body = str_replace($staff_searcharray,$staff_replacearray,$message);
								/* MESSAGE SENDING CODE THROUGH PLIVO */
								$params = array(
										'src' => $plivo_sender_number,
										'dst' => $phone,
										'text' => $client_sms_body,
										'method' => 'POST'
								);
								print_r($params);
								$response = $p_client->send_message($params);
								/* MESSAGE SENDING CODE ENDED HERE*/
							}
						}
					}
				}
				if($setting->get_option('ld_sms_twilio_status') == "Y"){
					if($setting->get_option('ld_sms_twilio_send_sms_to_staff_status') == "Y"){
						if(isset($staff_phone) && !empty($staff_phone))
						{
							$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
							$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
							$twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

							$template = $objdashboard->gettemplate_sms("C",'S');
							$phone = $staff_phone;
							if($template[4] == "E") {
									if($template[2] == ""){
											$message = base64_decode($template[3]);
									}
									else{
											$message = base64_decode($template[2]);
									}
									$client_sms_body = str_replace($staff_searcharray,$staff_replacearray,$message);
									/*TWILIO CODE*/
									$message = $twilliosms_client->account->messages->create(array(
											"From" => $twilio_sender_number,
											"To" => $phone,
											"Body" => $client_sms_body));
							}
						}
					}
				}
				if($setting->get_option('ld_nexmo_status') == "Y"){
					if($setting->get_option('ld_sms_nexmo_send_sms_to_staff_status') == "Y"){
						if(isset($staff_phone) && !empty($staff_phone))
						{	
							$template = $objdashboard->gettemplate_sms("C",'S');
							$phone = $staff_phone;				
							if($template[4] == "E") {
								if($template[2] == ""){
									$message = base64_decode($template[3]);
								}
								else{
									$message = base64_decode($template[2]);
								}
								$ld_nexmo_text = str_replace($staff_searcharray,$staff_replacearray,$message);
								$res=$nexmo_client->send_nexmo_sms($phone,$ld_nexmo_text);
							}
						}
					}
				}
			}
		}
	}

    /*SMS SENDING CODE*/
    /*GET APPROVED SMS TEMPLATE*/
	/* TEXTLOCAL CODE */
	if($setting->get_option('ld_sms_textlocal_status') == "Y")
	{
		if($setting->get_option('ld_sms_textlocal_send_sms_to_client_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("C",'C');
			$phone = $client_phone;				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
			}
			$message = str_replace($searcharray,$replacearray,$message);
			$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
			$ch = curl_init('http://api.textlocal.in/send/?');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
		}
		if($setting->get_option('ld_sms_textlocal_send_sms_to_admin_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("C",'A');
			$phone = $setting->get_option('ld_sms_textlocal_admin_phone');				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
			}
			$message = str_replace($searcharray,$replacearray,$message);
			$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
			$ch = curl_init('http://api.textlocal.in/send/?');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
		}
		
		if($setting->get_option('ld_sms_textlocal_send_sms_to_staff_status') == "Y"){
			if(isset($staff_phone) && !empty($staff_phone))
			{	
				$template = $objdashboard->gettemplate_sms("C",'S');
				$phone = $staff_phone;				
				if($template[4] == "E") {
					if($template[2] == ""){
						$message = base64_decode($template[3]);
					}
					else{
						$message = base64_decode($template[2]);
					}
				}
				$message = str_replace($searcharray,$replacearray,$message);
				$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
				$ch = curl_init('http://api.textlocal.in/send/?');
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);
			}
		}
	}
    /*PLIVO CODE*/
        if($setting->get_option('ld_sms_plivo_status')=="Y"){
           
		   if($setting->get_option('ld_sms_plivo_send_sms_to_client_status') == "Y"){
                $auth_id = $setting->get_option('ld_sms_plivo_account_SID');
				$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
				$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');
				$template = $objdashboard->gettemplate_sms("C",'C');
                $phone = $client_phone;
                if($template[4] == "E"){
                    if($template[2] == ""){
                        $message = base64_decode($template[3]);
                    }
                    else{
                        $message = base64_decode($template[2]);
                    }
                    $client_sms_body = str_replace($searcharray,$replacearray,$message);
                    /* MESSAGE SENDING CODE THROUGH PLIVO */
                    $params = array(
                        'src' => $plivo_sender_number,
                        'dst' => $phone,
                        'text' => $client_sms_body,
                        'method' => 'POST'
                    );
					$response = $p_client->send_message($params);
                    /* MESSAGE SENDING CODE ENDED HERE*/
                }
            }
            if($setting->get_option('ld_sms_plivo_send_sms_to_admin_status') == "Y"){
                $auth_id = $setting->get_option('ld_sms_plivo_account_SID');
				$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
				$p_admin = new Plivo\RestAPI($auth_id, $auth_token, '', '');
				$template = $objdashboard->gettemplate_sms("C",'A');
                $phone = $admin_phone_plivo;
                if($template[4] == "E") {
                    if($template[2] == ""){
                        $message = base64_decode($template[3]);
                    }
                    else{
                        $message = base64_decode($template[2]);
                    }
                    $client_sms_body = str_replace($searcharray,$replacearray,$message);
                    $params = array(
                        'src' => $plivo_sender_number,
                        'dst' => $phone,
                        'text' => $client_sms_body,
                        'method' => 'POST'
                    );
					$response = $p_admin->send_message($params);
                    /* MESSAGE SENDING CODE ENDED HERE*/
                }
            }
						
						if($setting->get_option('ld_sms_plivo_send_sms_to_staff_status') == "Y"){
							if(isset($staff_phone) && !empty($staff_phone))
							{
								$auth_id = $setting->get_option('ld_sms_plivo_account_SID');
								$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
								$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');
								$template = $objdashboard->gettemplate_sms("C",'S');
								$phone = $staff_phone;
								if($template[4] == "E"){
									if($template[2] == ""){
											$message = base64_decode($template[3]);
									}
									else{
											$message = base64_decode($template[2]);
									}
									$client_sms_body = str_replace($searcharray,$replacearray,$message);
									/* MESSAGE SENDING CODE THROUGH PLIVO */
									$params = array(
											'src' => $plivo_sender_number,
											'dst' => $phone,
											'text' => $client_sms_body,
											'method' => 'POST'
									);
									$response = $p_client->send_message($params);
									/* MESSAGE SENDING CODE ENDED HERE*/
								}
							}
            }
        }
        if($setting->get_option('ld_sms_twilio_status') == "Y"){
            if($setting->get_option('ld_sms_twilio_send_sms_to_client_status') == "Y"){
				$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
				$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
				$twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

				$template = $objdashboard->gettemplate_sms("C",'C');
                $phone = $client_phone;
                if($template[4] == "E") {
                    if($template[2] == ""){
                        $message = base64_decode($template[3]);
                    }
                    else{
                        $message = base64_decode($template[2]);
                    }
                    $client_sms_body = str_replace($searcharray,$replacearray,$message);
                    /*TWILIO CODE*/
                    $message = $twilliosms_client->account->messages->create(array(
                        "From" => $twilio_sender_number,
                        "To" => $phone,
                        "Body" => $client_sms_body));
                }
            }
            if($setting->get_option('ld_sms_twilio_send_sms_to_admin_status') == "Y"){
				$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
				$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
				$twilliosms_admin = new Services_Twilio($AccountSid, $AuthToken);

				$template = $objdashboard->gettemplate_sms("C",'A');
                $phone = $admin_phone_twilio;
                if($template[4] == "E") {
                    if($template[2] == ""){
                        $message = base64_decode($template[3]);
                    }
                    else{
                        $message = base64_decode($template[2]);
                    }
                    $client_sms_body = str_replace($searcharray,$replacearray,$message);
                    /*TWILIO CODE*/
                    $message = $twilliosms_admin->account->messages->create(array(
                        "From" => $twilio_sender_number,
                        "To" => $phone,
                        "Body" => $client_sms_body));
                }
            }
						
						if($setting->get_option('ld_sms_twilio_send_sms_to_staff_status') == "Y"){
							if(isset($staff_phone) && !empty($staff_phone))
							{	
								$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
								$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
								$twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

								$template = $objdashboard->gettemplate_sms("C",'S');
								$phone = $staff_phone;
								if($template[4] == "E") {
									if($template[2] == ""){
											$message = base64_decode($template[3]);
									}
									else{
											$message = base64_decode($template[2]);
									}
									$client_sms_body = str_replace($searcharray,$replacearray,$message);
									/*TWILIO CODE*/
									$message = $twilliosms_client->account->messages->create(array(
											"From" => $twilio_sender_number,
											"To" => $phone,
											"Body" => $client_sms_body));
								}
							}
            }
        }
		if($setting->get_option('ld_nexmo_status') == "Y"){
			if($setting->get_option('ld_sms_nexmo_send_sms_to_client_status') == "Y"){
				$template = $objdashboard->gettemplate_sms("C",'C');
				$phone = $client_phone;				
				if($template[4] == "E") {
					if($template[2] == ""){
						$message = base64_decode($template[3]);
					}
					else{
						$message = base64_decode($template[2]);
					}
				}
				$ld_nexmo_text = str_replace($searcharray,$replacearray,$message);
				$res=$nexmo_client->send_nexmo_sms($phone,$ld_nexmo_text);
			}
			if($setting->get_option('ld_sms_nexmo_send_sms_to_admin_status') == "Y"){
				$template = $objdashboard->gettemplate_sms("C",'A');
				$phone = $setting->get_option('ld_sms_nexmo_admin_phone_number');				
				if($template[4] == "E") {
					if($template[2] == ""){
						$message = base64_decode($template[3]);
					}
					else{
						$message = base64_decode($template[2]);
					}
					$ld_nexmo_text = str_replace($searcharray,$replacearray,$message);
					$res=$nexmo_admin->send_nexmo_sms($phone,$ld_nexmo_text);
				}
				
			}
			
			if($setting->get_option('ld_sms_nexmo_send_sms_to_staff_status') == "Y"){
				if(isset($staff_phone) && !empty($staff_phone))
				{
					$template = $objdashboard->gettemplate_sms("C",'S');
					$phone = $staff_phone;				
					if($template[4] == "E") {
						if($template[2] == ""){
							$message = base64_decode($template[3]);
						}
						else{
							$message = base64_decode($template[2]);
						}
					}
					$ld_nexmo_text = str_replace($searcharray,$replacearray,$message);
					$res=$nexmo_client->send_nexmo_sms($phone,$ld_nexmo_text);
				}
			}
		}
    /*SMS SENDING CODE END*/
}
elseif(isset($_POST['confirm_booking_cal'])){
    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING); /*here id is order id*/
    $orderdetail = $objdashboard->getclientorder($id);
    $lastmodify = date('Y-m-d H:i:s');
    /* Update Confirm status in bookings */
    $objdashboard->confirm_bookings($id,$lastmodify);

    $clientdetail = $objdashboard->clientemailsender($id);
	
	$admin_company_name = $setting->get_option('ld_company_name');
	$setting_date_format = $setting->get_option('ld_date_picker_date_format');
	$setting_time_format = $setting->get_option('ld_time_format');
	$booking_date = str_replace($english_date_array,$selected_lang_label,date($setting_date_format,strtotime($clientdetail['booking_pickup_date_time_start'])));
	if($setting_time_format == 12){
				$booking_time = str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($clientdetail['booking_pickup_date_time_start'])));
			}
			else{
				$booking_time = date("H:i", strtotime($clientdetail['booking_pickup_date_time_start']));
			}
	
    $company_name = $setting->get_option('ld_email_sender_name');
    $company_email = $setting->get_option('ld_email_sender_address');
    $service_name = $clientdetail['title'];
    if($admin_email == ""){
		$admin_email = $clientdetail['email'];	
	}
   
    $price=$general->ld_price_format($orderdetail[2],$symbol_position,$decimal);

    /* units */		
		$units = $label_language_values['none'];
		$book_unit_detail = $booking->get_booking_units_from_order($id);
		if($book_unit_detail->num_rows > 0)
		{
			$units_array = array();
			while($unit_row = mysqli_fetch_assoc($book_unit_detail))
			{
				$units_array[] = $unit_row["unit_name"]." - ".$unit_row["unit_qty"];
			}
			$units = implode(", ",$units_array);
		}


    /*if this is guest user than */
    if($orderdetail[3]==0)
    {
        $gc  = $objdashboard->getguestclient($orderdetail[4]);
        $temppp= unserialize(base64_decode($gc[5]));
        $temp = str_replace('\\','',$temppp);

        $client_name=$gc[2];
        $client_email=$gc[3];
		
		
		$phone_length = strlen($gc[4]);
			
			if($phone_length > 6){
				$client_phone = $gc[4];
			}else{
				$client_phone = "N/A";
			}
			
        $firstname=$client_name;
        $lastname='';
        $booking_status=$orderdetail[6];
        $payment_status=$orderdetail[5];
        $client_address=$temp['address'];
        $client_notes=$temp['notes'];
        $client_status=$temp['contact_status'];		$client_city = $temp['city'];		$client_state = $temp['state'];		$client_zip	= $temp['zip'];
    }
    else
        /*Registered user */
    {
        $c  = $objdashboard->getguestclient($orderdetail[4]);
        $temppp= unserialize(base64_decode($c[5]));
        $temp = str_replace('\\','',$temppp);
        $client_name=$c[2];
      
        $client_email=$c[3];
        
		
		$phone_length = strlen($c[4]);
			
			if($phone_length > 6){
				$client_phone = $c[4];
			}else{
				$client_phone = "N/A";
			}
			
			
			$client_name_value="";
			$client_first_name="";
			$client_last_name="";
			
			$client_name_value= explode(" ",$client_name);
			$client_first_name = $client_name_value[0];
			$client_last_name =	$client_name_value[1];
	
					if($client_first_name=="" && $client_last_name==""){
						$firstname = "User";
						$lastname = "";
						$client_name = $firstname.' '.$lastname;
					}elseif($client_first_name!="" && $client_last_name!=""){
						$firstname = $client_first_name;
						$lastname = $client_last_name;
						$client_name = $firstname.' '.$lastname;
					}elseif($client_first_name!=""){
						$firstname = $client_first_name;
						$lastname = "";
						$client_name = $firstname.' '.$lastname;
					}elseif($client_last_name!=""){
						$firstname = "";
						$lastname = $client_last_name;
						$client_name = $firstname.' '.$lastname;
					}
	
			$client_notes = $temp['notes'];	
					if($client_notes==""){
						$client_notes = "N/A";
					}		
			
			$client_status = $temp['contact_status'];	
					if($client_status==""){
						$client_status = "N/A";
					}	
			
			
			
			
        $payment_status=$orderdetail[5];
        $client_address=$temp['address'];	
		$client_city = $temp['city'];		
		$client_state = $temp['state'];		
		$client_zip	= $temp['zip'];
    }
		$payment_status = strtolower($payment_status);
		if($payment_status == "pay at venue"){
			$payment_status = ucwords($label_language_values['pay_locally']);
		}else{
			$payment_status = ucwords($payment_status);
		}
    $searcharray = array('{{service_name}}','{{booking_date}}','{{business_logo}}','{{business_logo_alt}}','{{client_name}}','{{units}}','{{client_email}}','{{phone}}','{{payment_method}}','{{notes}}','{{contact_status}}','{{address}}','{{price}}','{{admin_name}}','{{firstname}}','{{lastname}}','{{app_remain_time}}','{{reject_status}}','{{company_name}}','{{booking_time}}','{{client_city}}','{{client_state}}','{{client_zip}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_address}}','{{admin_name}}');

    $replacearray = array($service_name, $booking_date , $business_logo, $business_logo_alt, $client_name, $units, $client_email, $client_phone, $payment_status, $client_notes, $client_status,$client_address,$price,$get_admin_name,$firstname,$lastname,'','',$admin_company_name,$booking_time,$client_city,$client_state,$client_zip,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_address,$get_admin_name);

    $emailtemplate->email_subject="Appointment Approved";
    $emailtemplate->user_type="C";
		
    $clientemailtemplate=$emailtemplate->readone_client_email_template_body();

    if($clientemailtemplate[2] != ''){
        $clienttemplate = base64_decode($clientemailtemplate[2]);
    }else{
        $clienttemplate = base64_decode($clientemailtemplate[3]);
    }
	$subject=$label_language_values[strtolower(str_replace(" ","_",$clientemailtemplate[1]))];
    
   
    if($setting->get_option('ld_client_email_notification_status') == 'Y' && $clientemailtemplate[4]=='E' ){
        $client_email_body = str_replace($searcharray,$replacearray,$clienttemplate);
        if($setting->get_option('ld_smtp_hostname') != '' && $setting->get_option('ld_email_sender_name') != '' && $setting->get_option('ld_email_sender_address') != '' && $setting->get_option('ld_smtp_username') != '' && $setting->get_option('ld_smtp_password') != '' && $setting->get_option('ld_smtp_port') != ''){
            $mail->IsSMTP();
        }else{
            $mail->IsMail();
        }
        $mail->SMTPDebug  = 0;
        $mail->IsHTML(true);
        $mail->From = $company_email;
        $mail->FromName = $company_name;
        $mail->Sender = $company_email;
        $mail->AddAddress($client_email, $client_name);
        $mail->Subject = $subject;
        $mail->Body = $client_email_body;
        $mail->send();
		$mail->ClearAllRecipients();
    }
	
    /*** Email Code End ***/

    /*** Email Code Start ***/
    $emailtemplate->email_subject="Appointment Approved";
    $emailtemplate->user_type="A";
    $adminemailtemplate=$emailtemplate->readone_client_email_template_body();

    if($adminemailtemplate[2] != ''){
        $admintemplate = base64_decode($adminemailtemplate[2]);
    }else{
        $admintemplate = base64_decode($adminemailtemplate[3]);
    }
	$adminsubject=$label_language_values[strtolower(str_replace(" ","_",$adminemailtemplate[1]))];

    if($setting->get_option('ld_admin_email_notification_status')=='Y' && $adminemailtemplate[4]=='E'){
        $admin_email_body = str_replace($searcharray,$replacearray,$admintemplate);

        if($setting->get_option('ld_smtp_hostname') != '' && $setting->get_option('ld_email_sender_name') != '' && $setting->get_option('ld_email_sender_address') != '' && $setting->get_option('ld_smtp_username') != '' && $setting->get_option('ld_smtp_password') != '' && $setting->get_option('ld_smtp_port') != ''){
            $mail_a->IsSMTP();
        }else{
            $mail_a->IsMail();
        }
        $mail_a->SMTPDebug  = 0;
        $mail_a->IsHTML(true);
        $mail_a->From = $company_email;
        $mail_a->FromName = $company_name;
        $mail_a->Sender = $company_email;
        $mail_a->AddAddress($admin_email, $get_admin_name);
        $mail_a->Subject = $adminsubject;
        $mail_a->Body = $admin_email_body;
        $mail_a->send();
		$mail_a->ClearAllRecipients();
    }
	$staff_ids = $booking->get_staff_ids_from_bookings($id);
	if($staff_ids != ''){
		$staff_idss = explode(',',$staff_ids);
		if(sizeof($staff_idss) > 0){
			foreach($staff_idss as $sid){
				$staffdetails = $booking->get_staff_detail_for_email($sid);
				$staff_name = $staffdetails['fullname'];
				$staff_email = $staffdetails['email'];		
				$staff_phone = $staffdetails['phone'];			
				$staff_searcharray = array('{{service_name}}','{{booking_date}}','{{business_logo}}','{{business_logo_alt}}','{{client_name}}','{{units}}','{{client_email}}','{{phone}}','{{payment_method}}','{{notes}}','{{contact_status}}','{{address}}','{{price}}','{{admin_name}}','{{firstname}}','{{lastname}}','{{app_remain_time}}','{{reject_status}}','{{company_name}}','{{booking_time}}','{{client_city}}','{{client_state}}','{{client_zip}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_address}}','{{admin_name}}','{{staff_name}}','{{staff_email}}');
					
				$staff_replacearray = array($service_name, $booking_date , $business_logo, $business_logo_alt, $client_name, $units, $client_email, $client_phone, $payment_status, $client_notes, $client_status,$client_address,$price,$get_admin_name,$firstname,$lastname,'','',$admin_company_name,$booking_time,$client_city,$client_state,$client_zip,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_address,$get_admin_name,$staff_name,$staff_email);
			
				$emailtemplate->email_subject="Appointment Approved";
				$emailtemplate->user_type="S";
				$staffemailtemplate=$emailtemplate->readone_client_email_template_body();
				if($staffemailtemplate[2] != ''){
					$stafftemplate = base64_decode($staffemailtemplate[2]);
				}else{
					$stafftemplate = base64_decode($staffemailtemplate[3]);
				}
				
				$subject=$label_language_values[strtolower(str_replace(" ","_",$staffemailtemplate[1]))];
			   
				if($setting->get_option('ld_staff_email_notification_status') == 'Y' && $staffemailtemplate[4]=='E' ){
					$client_email_body = str_replace($staff_searcharray,$staff_replacearray,$stafftemplate);
					if($setting->get_option('ld_smtp_hostname') != '' && $setting->get_option('ld_email_sender_name') != '' && $setting->get_option('ld_email_sender_address') != '' && $setting->get_option('ld_smtp_username') != '' && $setting->get_option('ld_smtp_password') != '' && $setting->get_option('ld_smtp_port') != ''){
						$mail_s->IsSMTP();
					}else{
						$mail_s->IsMail();
					}
					$mail_s->SMTPDebug  = 0;
					$mail_s->IsHTML(true);
					$mail_s->From = $company_email;
					$mail_s->FromName = $company_name;
					$mail_s->Sender = $company_email;
					$mail_s->AddAddress($staff_email, $staff_name);
					$mail_s->Subject = $subject;
					$mail_s->Body = $client_email_body;
					$mail_s->send();
					$mail_s->ClearAllRecipients();
				}
				
				/* TEXTLOCAL CODE */
				if($setting->get_option('ld_sms_textlocal_status') == "Y")
				{
					if($setting->get_option('ld_sms_textlocal_send_sms_to_staff_status') == "Y"){
						if(isset($staff_phone) && !empty($staff_phone))
						{
							$template = $objdashboard->gettemplate_sms("C",'S');
							$phone = $staff_phone;				
							if($template[4] == "E") {
								if($template[2] == ""){
									$message = base64_decode($template[3]);
								}
								else{
									$message = base64_decode($template[2]);
								}
							}
							$message = str_replace($staff_searcharray,$staff_replacearray,$message);
							$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
							
							$ch = curl_init('http://api.textlocal.in/send/?');
							curl_setopt($ch, CURLOPT_POST, true);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$result = curl_exec($ch);
							curl_close($ch);
						}
					}
				}
				/*PLIVO CODE*/
				if($setting->get_option('ld_sms_plivo_status')=="Y"){
					if($setting->get_option('ld_sms_plivo_send_sms_to_staff_status') == "Y"){
						if(isset($staff_phone) && !empty($staff_phone))
						{	
							$auth_id = $setting->get_option('ld_sms_plivo_account_SID');
							$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
							$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');

							$template = $objdashboard->gettemplate_sms("C",'S');
							$phone = $staff_phone;
							if($template[4] == "E"){
								if($template[2] == ""){
										$message = base64_decode($template[3]);
								}
								else{
										$message = base64_decode($template[2]);
								}
								$client_sms_body = str_replace($staff_searcharray,$staff_replacearray,$message);
								/* MESSAGE SENDING CODE THROUGH PLIVO */
								$params = array(
										'src' => $plivo_sender_number,
										'dst' => $phone,
										'text' => $client_sms_body,
										'method' => 'POST'
								);
								print_r($params);
								$response = $p_client->send_message($params);
								/* MESSAGE SENDING CODE ENDED HERE*/
							}
						}
					}
				}
				if($setting->get_option('ld_sms_twilio_status') == "Y"){
					if($setting->get_option('ld_sms_twilio_send_sms_to_staff_status') == "Y"){
						if(isset($staff_phone) && !empty($staff_phone))
						{
							$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
							$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
							$twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

							$template = $objdashboard->gettemplate_sms("C",'S');
							$phone = $staff_phone;
							if($template[4] == "E") {
									if($template[2] == ""){
											$message = base64_decode($template[3]);
									}
									else{
											$message = base64_decode($template[2]);
									}
									$client_sms_body = str_replace($staff_searcharray,$staff_replacearray,$message);
									/*TWILIO CODE*/
									$message = $twilliosms_client->account->messages->create(array(
											"From" => $twilio_sender_number,
											"To" => $phone,
											"Body" => $client_sms_body));
							}
						}
					}
				}
				if($setting->get_option('ld_nexmo_status') == "Y"){
					if($setting->get_option('ld_sms_nexmo_send_sms_to_staff_status') == "Y"){
						if(isset($staff_phone) && !empty($staff_phone))
						{	
							$template = $objdashboard->gettemplate_sms("C",'S');
							$phone = $staff_phone;				
							if($template[4] == "E") {
								if($template[2] == ""){
									$message = base64_decode($template[3]);
								}
								else{
									$message = base64_decode($template[2]);
								}
								$ld_nexmo_text = str_replace($staff_searcharray,$staff_replacearray,$message);
								$res=$nexmo_client->send_nexmo_sms($phone,$ld_nexmo_text);
							}
						}
					}
				}
					/*SMS SENDING CODE END*/
			}
		}
	}
    /*** Email Code End ***/

    /*SMS SENDING CODE*/
    /*GET APPROVED SMS TEMPLATE*/
	/* TEXTLOCAL CODE */
	if($setting->get_option('ld_sms_textlocal_status') == "Y")
	{
		if($setting->get_option('ld_sms_textlocal_send_sms_to_client_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("C",'C');
			$phone = $client_phone;				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
			}
			$message = str_replace($searcharray,$replacearray,$message);
			$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
			$ch = curl_init('http://api.textlocal.in/send/?');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
		}
		if($setting->get_option('ld_sms_textlocal_send_sms_to_admin_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("C",'A');
			$phone = $setting->get_option('ld_sms_textlocal_admin_phone');				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
			}
			$message = str_replace($searcharray,$replacearray,$message);
			$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
			$ch = curl_init('http://api.textlocal.in/send/?');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
    /*PLIVO CODE*/
    if($setting->get_option('ld_sms_plivo_status')=="Y"){
        if($setting->get_option('ld_sms_plivo_send_sms_to_client_status') == "Y"){
            $auth_id = $setting->get_option('ld_sms_plivo_account_SID');
			$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
			$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');

			$template = $objdashboard->gettemplate_sms("C",'C');
            $phone = $client_phone;
            if($template[4] == "E"){
                if($template[2] == ""){
                    $message = base64_decode($template[3]);
                }
                else{
                    $message = base64_decode($template[2]);
                }
                $client_sms_body = str_replace($searcharray,$replacearray,$message);
                /* MESSAGE SENDING CODE THROUGH PLIVO */
                $params = array(
                    'src' => $plivo_sender_number,
                    'dst' => $phone,
                    'text' => $client_sms_body,
                    'method' => 'POST'
                );
                print_r($params);
                $response = $p_client->send_message($params);
                /* MESSAGE SENDING CODE ENDED HERE*/
            }
        }
        if($setting->get_option('ld_sms_plivo_send_sms_to_admin_status') == "Y"){
            $auth_id = $setting->get_option('ld_sms_plivo_account_SID');
			$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
			$p_admin = new Plivo\RestAPI($auth_id, $auth_token, '', '');

			$template = $objdashboard->gettemplate_sms("C",'A');
            $phone = $admin_phone_plivo;
            if($template[4] == "E") {
                if($template[2] == ""){
                    $message = base64_decode($template[3]);
                }
                else{
                    $message = base64_decode($template[2]);
                }
                $client_sms_body = str_replace($searcharray,$replacearray,$message);
                $params = array(
                    'src' => $plivo_sender_number,
                    'dst' => $phone,
                    'text' => $client_sms_body,
                    'method' => 'POST'
                );
                $response = $p_admin->send_message($params);
                /* MESSAGE SENDING CODE ENDED HERE*/
            }
        }
    }
    if($setting->get_option('ld_sms_twilio_status') == "Y"){
        if($setting->get_option('ld_sms_twilio_send_sms_to_client_status') == "Y"){
			$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
			$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
			$twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

			$template = $objdashboard->gettemplate_sms("C",'C');
            $phone = $client_phone;
            if($template[4] == "E") {
                if($template[2] == ""){
                    $message = base64_decode($template[3]);
                }
                else{
                    $message = base64_decode($template[2]);
                }
                $client_sms_body = str_replace($searcharray,$replacearray,$message);
                /*TWILIO CODE*/
                $message = $twilliosms_client->account->messages->create(array(
                    "From" => $twilio_sender_number,
                    "To" => $phone,
                    "Body" => $client_sms_body));
            }
        }
        if($setting->get_option('ld_sms_twilio_send_sms_to_admin_status') == "Y"){
			$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
			$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
			$twilliosms_admin = new Services_Twilio($AccountSid, $AuthToken);

			$template = $objdashboard->gettemplate_sms("C",'A');
            $phone = $admin_phone_twilio;
            if($template[4] == "E") {
                if($template[2] == ""){
                    $message = base64_decode($template[3]);
                }
                else{
                    $message = base64_decode($template[2]);
                }
                $client_sms_body = str_replace($searcharray,$replacearray,$message);
                /*TWILIO CODE*/
                $message = $twilliosms_admin->account->messages->create(array(
                    "From" => $twilio_sender_number,
                    "To" => $phone,
                    "Body" => $client_sms_body));
            }
        }
    }
	if($setting->get_option('ld_nexmo_status') == "Y"){
		if($setting->get_option('ld_sms_nexmo_send_sms_to_client_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("C",'C');
			$phone = $client_phone;				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
				$ld_nexmo_text = str_replace($searcharray,$replacearray,$message);
				$res=$nexmo_client->send_nexmo_sms($phone,$ld_nexmo_text);
			}
			
		}
		if($setting->get_option('ld_sms_nexmo_send_sms_to_admin_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("C",'A');
			$phone = $setting->get_option('ld_sms_nexmo_admin_phone_number');				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
				$ld_nexmo_text = str_replace($searcharray,$replacearray,$message);
				$res=$nexmo_admin->send_nexmo_sms($phone,$ld_nexmo_text);
			}
			
		}
	}
    /*SMS SENDING CODE END*/

}

elseif(isset($_POST['getallnotification'])){
	
    $books = $objdashboard->getallbookings_notify();
    while($b = mysqli_fetch_array($books))
	{
		if($b['read_status'] =='U')
			$col = "#f8f8f8";
		else
			$col = "#fff";
		?>
		<li id="rec-noti-1" class="notificationli" data-orderid="<?php echo filter_var($b['order_id'], FILTER_SANITIZE_STRING);?>" style="background-color: <?php  echo filter_var($col, FILTER_SANITIZE_STRING);?>" data-toggle="modal" data-target="#booking-details-dashboard">
			<div class="list-inner">
				<?php 
				if($b['client_id']==0)
				{
					$gc  = $objdashboard->getguestclient($b['order_id']);
					?>
					<?php 
					if($b['booking_status']=='A')
					{
						$booking_stats='<span class="ld-label bg-info br-2">'.$label_language_values['active'].'</span>';
					}
					elseif($b['booking_status']=='C')
					{
						$booking_stats='<span class="ld-label bg-success br-2">'.$label_language_values['confirmed'].'</span>';
					}
					elseif($b['booking_status']=='R')
					{
						$booking_stats='<span class="ld-label bg-danger br-2">'.$label_language_values['rejected'].'</span>';
					}
					elseif($b['booking_status']=='RS')
					{
						$booking_stats='<span class="ld-label bg-primary br-2">'.$label_language_values['rescheduled'].'</span>';
					}
					elseif($b['booking_status']=='CC')
					{
						$booking_stats='<span class="ld-label bg-warning br-2">'.$label_language_values['cancelled_by_client'].'</span>';
					}
					elseif($b['booking_status']=='CS')
					{

						$booking_stats='<span class="ld-label bg-danger br-2">'.$label_language_values['cancelled_by_service_provider'].'</span>';
					}
					elseif($b['booking_status']=='CO')
					{
						$booking_stats='<span class="ld-label bg-success br-2">'.$label_language_values['completed'].'</span>';
					}
					else
					{
						$booking_stats='<span class="ld-label bg-default br-2">'.$label_language_values['mark_as_no_show'].'</span>';
					}
					?>
					<span class="booking-text"><?php echo filter_var($booking_stats, FILTER_SANITIZE_STRING);?> <?php  echo filter_var($gc[2], FILTER_SANITIZE_STRING)." ".filter_var($label_language_values['for_a'], FILTER_SANITIZE_STRING)." ".$b['title']." ".filter_var($label_language_values['on'], FILTER_SANITIZE_STRING)." ".str_replace($english_date_array,$selected_lang_label,date($getdateformate, strtotime($b['booking_pickup_date_time_start'])));?> @ <?php 
					if($time_format == 12){
					?>
					<?php  echo str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($b['booking_pickup_date_time_start'])));?></span>
					<?php 
					}else{
					?>
					<?php  echo date("H:i", strtotime($b['booking_pickup_date_time_start']));?></span>
					<?php 
					}
					?></span>
					<span class="booking-time">
							<?php 
							echo time_elapsed_string($b['lastmodify']);
							?>
							</span>
				<?php 
				}
				else
				{
					$c  = $objdashboard->getclient($b['client_id']);
					?>
					<?php 
					if($b['booking_status']=='A')
					{
						$booking_stats='<span class="ld-label bg-info br-2">'.$label_language_values['active'].'</span>';
					}
					elseif($b['booking_status']=='C')
					{
						$booking_stats='<span class="ld-label bg-success br-2">'.$label_language_values['confirmed'].'</span>';
					}
					elseif($b['booking_status']=='R')
					{
						$booking_stats='<span class="ld-label bg-danger br-2">'.$label_language_values['rejected'].'</span>';
					}
					elseif($b['booking_status']=='RS')
					{
						$booking_stats='<span class="ld-label bg-primary br-2">'.$label_language_values['rescheduled'].'</span>';
					}
					elseif($b['booking_status']=='CC')
					{
						$booking_stats='<span class="ld-label bg-warning br-2">'.$label_language_values['cancelled_by_client'].'</span>';
					}
					elseif($b['booking_status']=='CS')
					{

						$booking_stats='<span class="ld-label bg-danger br-2">'.$label_language_values['cancelled_by_service_provider'].'</span>';
					}
					elseif($b['booking_status']=='CO')
					{
						$booking_stats='<span class="ld-label bg-success br-2">'.$label_language_values['completed'].'</span>';
					}
					else
					{
						$booking_stats='<span class="ld-label bg-default br-2">'.$label_language_values['mark_as_no_show'].'</span>';
					}
					?>
					<span class="booking-text"><?php echo filter_var($booking_stats, FILTER_SANITIZE_STRING);?> <?php  echo filter_var($c[1], FILTER_SANITIZE_STRING)." ".filter_var($label_language_values['for_a'], FILTER_SANITIZE_STRING)." ";?>  <?php  echo filter_var($b['title'], FILTER_SANITIZE_STRING)." ".filter_var($label_language_values['on'], FILTER_SANITIZE_STRING)." ";?><?php echo str_replace($english_date_array,$selected_lang_label,date($getdateformate, strtotime($b['booking_pickup_date_time_start'])));?> @ <?php 
					if($time_format == 12){
					?>
					<?php  echo str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($b['booking_pickup_date_time_start'])));?></span>
					<?php 
					}else{
					?>
					<?php  echo date("H:i", strtotime($b['booking_pickup_date_time_start']));?></span>
					<?php 
					}
					?></span>
					<span class="booking-time">
							<?php 
							echo time_elapsed_string($b['lastmodify']);
							?>
							</span>
				<?php 
				}
				?>
			</div>
		</li>
	<?php 
	}
}
elseif(isset($_POST['reject_booking'])){
    $id = filter_var($_POST['order_id'], FILTER_SANITIZE_STRING);
    $reason = filter_var($_POST['reject_reason_book'], FILTER_SANITIZE_STRING);
	$gc_event_id = filter_var($_POST['gc_event_id'], FILTER_SANITIZE_STRING);
    $lastmodify = date('Y-m-d H:i:s');
    $objdashboard->reject_bookings($id,$reason,$lastmodify);
	$client_name = "";
	
	$orderdetail = $objdashboard->getclientorder($id);
    $clientdetail = $objdashboard->clientemailsender($id);

	$pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
	$gc_staff_event_id = filter_var($_POST['gc_staff_event_id'], FILTER_SANITIZE_STRING);
	
	if($gc_hook->gc_purchase_status() == 'exist'){
		echo filter_var($gc_hook->gc_cancel_reject_booking_hook(), FILTER_SANITIZE_STRING);
	}
	
	$admin_company_name = $setting->get_option('ld_company_name');
	$setting_date_format = $setting->get_option('ld_date_picker_date_format');
	$setting_time_format = $setting->get_option('ld_time_format');
	$booking_date = str_replace($english_date_array,$selected_lang_label,date($setting_date_format,strtotime($clientdetail['booking_pickup_date_time_start'])));
	if($setting_time_format == 12){
		$booking_time = str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($clientdetail['booking_pickup_date_time_start'])));
	}
	else{
		$booking_time = date("H:i", strtotime($clientdetail['booking_pickup_date_time_start']));
	}
	$company_name = $setting->get_option('ld_email_sender_name');
    $company_email = $setting->get_option('ld_email_sender_address');
	$service_name = $clientdetail['title'];
	if($admin_email == ""){
		$admin_email = $clientdetail['email'];	
	}
	
	$price=$general->ld_price_format($orderdetail[2],$symbol_position,$decimal);
        
		/* methods */
		$units = $label_language_values['none'];
		$book_unit_detail = $booking->get_booking_units_from_order($orderdetail[4]);
		if($book_unit_detail->num_rows > 0)
		{
			$units_array = array();
			while($unit_row = mysqli_fetch_assoc($book_unit_detail))
			{
				$units_array[] = $unit_row["unit_name"]." - ".$unit_row["unit_qty"];
			}
			$units = implode(", ",$units_array);
		}
																	
		/*if this is guest user than */									
			if($orderdetail[3]==0)
			{
				$gc  = $objdashboard->getguestclient($orderdetail[4]);
				$temppp= unserialize(base64_decode($gc[5]));
				$temp = str_replace('\\','',$temppp);
					
				$client_name=$gc[2];
				$client_email=$gc[3];
				
				$client_phone=$gc[4];
				$firstname=$client_name;
				$lastname='';
				$booking_status=$orderdetail[6];
				$payment_status=$orderdetail[5];
				$client_address=$temp['address'];
				$client_notes=$temp['notes'];
				$client_status=$temp['contact_status'];
				$client_city = $temp['city'];				$client_state = $temp['state'];				$client_zip	= $temp['zip'];
			}
			else
			/*Registered user */
			{
				$c  = $objdashboard->getguestclient($orderdetail[4]);
				
				$temppp= unserialize(base64_decode($c[5]));
				$temp = str_replace('\\','',$temppp);
				$client_name=$c[2];
				$client_email=$c[3];
				$phone_length = strlen($c[4]);
			
			if($phone_length > 6){
				$client_phone = $c[4];
			}else{
				$client_phone = "N/A";
			}
			
			
			
			
			
			$client_name_value="";
			$client_first_name="";
			$client_last_name="";
			
			$client_namess= explode(" ",$client_name);
			$cnamess = array_filter($client_namess);
			$ccnames = array_values($cnamess);
			if(sizeof($ccnames)>0){
				$client_first_name =  $ccnames[0]; 
				if(isset($ccnames[1])){
					$client_last_name =  $ccnames[1]; 
				}else{
					$client_last_name =  ''; 
				}
			}else{
				$client_first_name =  ''; 
				$client_last_name =  ''; 
			}
					if($client_first_name=="" && $client_last_name==""){
						$firstname = "User";
						$lastname = "";
						$client_name = $firstname.' '.$lastname;
					}elseif($client_first_name!="" && $client_last_name!=""){
						$firstname = $client_first_name;
						$lastname = $client_last_name;
						$client_name = $firstname.' '.$lastname;
					}elseif($client_first_name!=""){
						$firstname = $client_first_name;
						$lastname = "";
						$client_name = $firstname.' '.$lastname;
					}elseif($client_last_name!=""){
						$firstname = "";
						$lastname = $client_last_name;
						$client_name = $firstname.' '.$lastname;
					}
					
			$client_notes = $temp['notes'];	
					if($client_notes==""){
						$client_notes = "N/A";
					}		
			
			$client_status = $temp['contact_status'];	
					if($client_status==""){
						$client_status = "N/A";
					}	
					
				$payment_status=$orderdetail[5];
				$client_address=$temp['address'];
				$client_city = $temp['city'];
				$client_state = $temp['state'];		
				$client_zip	= $temp['zip'];
			}					
		$payment_status = strtolower($payment_status);
		if($payment_status == "pay at venue"){
			$payment_status = ucwords($label_language_values['pay_locally']);
		}else{
			$payment_status = ucwords($payment_status);
		}
		$searcharray = array('{{service_name}}','{{booking_date}}','{{business_logo}}','{{business_logo_alt}}','{{client_name}}','{{units}}','{{client_email}}','{{phone}}','{{payment_method}}','{{notes}}','{{contact_status}}','{{address}}','{{price}}','{{admin_name}}','{{firstname}}','{{lastname}}','{{app_remain_time}}','{{reject_status}}','{{company_name}}','{{booking_time}}','{{client_city}}','{{client_state}}','{{client_zip}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_address}}','{{admin_name}}'); 
		
		$replacearray = array($service_name, $booking_date , $business_logo, $business_logo_alt, $client_name, $units, $client_email, $client_phone, $payment_status, $client_notes, $client_status,$client_address,$price,$get_admin_name,$firstname,$lastname,'',$reason,$admin_company_name,$booking_time,$client_city,$client_state,$client_zip,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_address,$get_admin_name);
		/* Client Email Template */
		
		$emailtemplate->email_subject="Appointment Rejected";
		$emailtemplate->user_type="C";
		$clientemailtemplate=$emailtemplate->readone_client_email_template_body();
		
		if($clientemailtemplate[2] != ''){
			$clienttemplate = base64_decode($clientemailtemplate[2]);
		}else{
			$clienttemplate = base64_decode($clientemailtemplate[3]);
		}
		$subject=$label_language_values[strtolower(str_replace(" ","_",$clientemailtemplate[1]))];
		
	if($setting->get_option('ld_client_email_notification_status') == 'Y' && $clientemailtemplate[4]=='E' ){
		
		$client_email_body = str_replace($searcharray,$replacearray,$clienttemplate);
		
        if($setting->get_option('ld_smtp_hostname') != '' && $setting->get_option('ld_email_sender_name') != '' && $setting->get_option('ld_email_sender_address') != '' && $setting->get_option('ld_smtp_username') != '' && $setting->get_option('ld_smtp_password') != '' && $setting->get_option('ld_smtp_port') != ''){
            $mail->IsSMTP();
        }else{
            $mail->IsMail();
        }
        $mail->SMTPDebug  = 0;
        $mail->IsHTML(true);
        $mail->From = $company_email;
        $mail->FromName = $company_name;
        $mail->Sender = $company_email;
        $mail->AddAddress($client_email, $client_name);
        $mail->Subject = $subject;
        $mail->Body = $client_email_body;
        $mail->send();
		$mail->ClearAllRecipients();
		
    }
   /* Admin Email template */
		$emailtemplate->email_subject="Appointment Rejected";
		$emailtemplate->user_type="A";
		$adminemailtemplate=$emailtemplate->readone_client_email_template_body();
		
		if($adminemailtemplate[2] != ''){
			$admintemplate = base64_decode($adminemailtemplate[2]);
		}else{
			$admintemplate = base64_decode($adminemailtemplate[3]);
		}
		$adminsubject=$label_language_values[strtolower(str_replace(" ","_",$adminemailtemplate[1]))];
	
    if($setting->get_option('ld_admin_email_notification_status')=='Y' && $adminemailtemplate[4]=='E'){
        $admin_email_body = str_replace($searcharray,$replacearray,$admintemplate);
		
		if($setting->get_option('ld_smtp_hostname') != '' && $setting->get_option('ld_email_sender_name') != '' && $setting->get_option('ld_email_sender_address') != '' && $setting->get_option('ld_smtp_username') != '' && $setting->get_option('ld_smtp_password') != '' && $setting->get_option('ld_smtp_port') != ''){
            $mail_a->IsSMTP();
        }else{
            $mail_a->IsMail();
        }
		
        $mail_a->SMTPDebug  = 0;
        $mail_a->IsHTML(true);
        $mail_a->From = $company_email;
        $mail_a->FromName = $company_name;
        $mail_a->Sender = $company_email;
        $mail_a->AddAddress($admin_email, $get_admin_name);
        $mail_a->Subject = $adminsubject;
        $mail_a->Body = $admin_email_body;
        $mail_a->send();
		$mail_a->ClearAllRecipients();
			
	}
	
	$staff_ids = $orderdetail[9];
	if($staff_ids != ''){
		$staff_idss = explode(',',$staff_ids);
		if(sizeof($staff_idss) > 0){
			foreach($staff_idss as $sid){
				$staffdetails = $booking->get_staff_detail_for_email($sid);
				$staff_name = $staffdetails['fullname'];
				$staff_email = $staffdetails['email'];		
				$staff_phone = $staffdetails['phone'];		
						
				$staff_searcharray = array('{{service_name}}','{{booking_date}}','{{business_logo}}','{{business_logo_alt}}','{{client_name}}','{{client_email}}','{{phone}}','{{payment_method}}','{{notes}}','{{contact_status}}','{{address}}','{{price}}','{{admin_name}}','{{firstname}}','{{lastname}}','{{app_remain_time}}','{{reject_status}}','{{company_name}}','{{booking_time}}','{{client_city}}','{{client_state}}','{{client_zip}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_address}}','{{admin_name}}','{{staff_name}}','{{staff_email}}');
					
				$staff_replacearray = array($service_name, $booking_date , $business_logo, $business_logo_alt, $client_name, $units, $client_email, $client_phone, $payment_status, $client_notes, $client_status,$client_address,$price,$get_admin_name,$firstname,$lastname,'','',$admin_company_name,$booking_time,$client_city,$client_state,$client_zip,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_address,$get_admin_name,$staff_name,$staff_email);
				
				
				$emailtemplate->email_subject="Appointment Rejected";
				$emailtemplate->user_type="S";
				$staffemailtemplate=$emailtemplate->readone_client_email_template_body();
				
				if($staffemailtemplate[2] != ''){
					$stafftemplate = base64_decode($staffemailtemplate[2]);
				}else{
					$stafftemplate = base64_decode($staffemailtemplate[3]);
				}
				$subject=$label_language_values[strtolower(str_replace(" ","_",$staffemailtemplate[1]))];
			   
				if($setting->get_option('ld_staff_email_notification_status') == 'Y' && $staffemailtemplate[4]=='E' ){
					$client_email_body = str_replace($staff_searcharray,$staff_replacearray,$stafftemplate);
					if($setting->get_option('ld_smtp_hostname') != '' && $setting->get_option('ld_email_sender_name') != '' && $setting->get_option('ld_email_sender_address') != '' && $setting->get_option('ld_smtp_username') != '' && $setting->get_option('ld_smtp_password') != '' && $setting->get_option('ld_smtp_port') != ''){
						$mail_s->IsSMTP();
					}else{
						$mail_s->IsMail();
					}
					$mail_s->SMTPDebug  = 0;
					$mail_s->IsHTML(true);
					$mail_s->From = $company_email;
					$mail_s->FromName = $company_name;
					$mail_s->Sender = $company_email;
					$mail_s->AddAddress($staff_email, $staff_name);
					$mail_s->Subject = $subject;
					$mail_s->Body = $client_email_body;
					$mail_s->send();
					$mail_s->ClearAllRecipients();
				}
			}
		}
	}

    /*** Email Code End ***/
    /*SMS SENDING CODE*/
    /*GET APPROVED SMS TEMPLATE*/
	/* TEXTLOCAL CODE */
	if($setting->get_option('ld_sms_textlocal_status') == "Y")
	{
		if($setting->get_option('ld_sms_textlocal_send_sms_to_client_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("R",'C');
			$phone = $client_phone;				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
			}
			$message = str_replace($searcharray,$replacearray,$message);
			$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
			$ch = curl_init('http://api.textlocal.in/send/?');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
		}
		if($setting->get_option('ld_sms_textlocal_send_sms_to_admin_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("R",'A');
			$phone = $setting->get_option('ld_sms_textlocal_admin_phone');				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
			}
			$message = str_replace($searcharray,$replacearray,$message);
			$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
			$ch = curl_init('http://api.textlocal.in/send/?');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
    /*PLIVO CODE*/
    if($setting->get_option('ld_sms_plivo_status')=="Y"){
        if($setting->get_option('ld_sms_plivo_send_sms_to_client_status') == "Y"){
            $auth_id = $setting->get_option('ld_sms_plivo_account_SID');
			$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
			$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');

			$template = $objdashboard->gettemplate_sms("R",'C');
            $phone = $client_phone;
            if($template[4] == "E"){
                if($template[2] == ""){
                    $message = base64_decode($template[3]);
                }
                else{
                    $message = base64_decode($template[2]);
                }
                $client_sms_body = str_replace($searcharray,$replacearray,$message);
                /* MESSAGE SENDING CODE THROUGH PLIVO */
                $params = array(
                    'src' => $plivo_sender_number,
                    'dst' => $phone,
                    'text' => $client_sms_body,
                    'method' => 'POST'
                );
                $response = $p_client->send_message($params);
                /* MESSAGE SENDING CODE ENDED HERE*/
            }
        }
        if($setting->get_option('ld_sms_plivo_send_sms_to_admin_status') == "Y"){
            $auth_id = $setting->get_option('ld_sms_plivo_account_SID');
			$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
			$p_admin = new Plivo\RestAPI($auth_id, $auth_token, '', '');

			$template = $objdashboard->gettemplate_sms("R",'A');
            $phone = $admin_phone_plivo;
            if($template[4] == "E") {
                if($template[2] == ""){
                    $message = base64_decode($template[3]);
                }
                else{
                    $message = base64_decode($template[2]);
                }
                $client_sms_body = str_replace($searcharray,$replacearray,$message);
                $params = array(
                    'src' => $plivo_sender_number,
                    'dst' => $phone,
                    'text' => $client_sms_body,
                    'method' => 'POST'
                );
                $response = $p_admin->send_message($params);
                /* MESSAGE SENDING CODE ENDED HERE*/
            }
        }
    }
    if($setting->get_option('ld_sms_twilio_status') == "Y"){
        if($setting->get_option('ld_sms_twilio_send_sms_to_client_status') == "Y"){
			$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
			$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
			$twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

			$template = $objdashboard->gettemplate_sms("R",'C');
            $phone = $client_phone;
            if($template[4] == "E") {
                if($template[2] == ""){
                    $message = base64_decode($template[3]);
                }
                else{
                    $message = base64_decode($template[2]);
                }
                $client_sms_body = str_replace($searcharray,$replacearray,$message);
                /*TWILIO CODE*/
                $message = $twilliosms_client->account->messages->create(array(
                    "From" => $twilio_sender_number,
                    "To" => $phone,
                    "Body" => $client_sms_body));
            }
        }
        if($setting->get_option('ld_sms_twilio_send_sms_to_admin_status') == "Y"){
			$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
			$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
			$twilliosms_admin = new Services_Twilio($AccountSid, $AuthToken);
			$phone = $admin_phone_twilio;
			$template = $objdashboard->gettemplate_sms("R",'A');
            if($template[4] == "E") {
                if($template[2] == ""){
                    $message = base64_decode($template[3]);
                }
                else{
                    $message = base64_decode($template[2]);
                }
                $client_sms_body = str_replace($searcharray,$replacearray,$message);
                /*TWILIO CODE*/
                $message = $twilliosms_admin->account->messages->create(array(
                    "From" => $twilio_sender_number,
                    "To" => $phone,
                    "Body" => $client_sms_body));
            }
        }
    }
	if($setting->get_option('ld_nexmo_status') == "Y"){
		if($setting->get_option('ld_sms_nexmo_send_sms_to_client_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("R",'C');
			$phone = $client_phone;				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
				$ld_nexmo_text = str_replace($searcharray,$replacearray,$message);
				$res=$nexmo_client->send_nexmo_sms($phone,$ld_nexmo_text);
			}
			
		}
		if($setting->get_option('ld_sms_nexmo_send_sms_to_admin_status') == "Y"){
			$template = $objdashboard->gettemplate_sms("R",'A');
			$phone = $setting->get_option('ld_sms_nexmo_admin_phone_number');				
			if($template[4] == "E") {
				if($template[2] == ""){
					$message = base64_decode($template[3]);
				}
				else{
					$message = base64_decode($template[2]);
				}
				$ld_nexmo_text = str_replace($searcharray,$replacearray,$message);
				$res=$nexmo_admin->send_nexmo_sms($phone,$ld_nexmo_text);
			}
			
		}
	}
	
	/* staff sms sending code */
		
	/* staff details */
	$staff_ids = $orderdetail[9];
	if(isset($staff_ids) && !empty($staff_ids))
	{
		$staff_id = array();
		$staff_id = explode(",",$staff_ids);
		foreach($staff_id as $stfid)
		{
			$objadminprofile->id = $stfid;
			$staff_details = $objadminprofile->readone();
			$get_staff_name = "";
			$get_staff_email = "";
			$staff_phone = "";
			if(isset($staff_details) && !empty($staff_details))
			{
				$get_staff_name = $staff_details["fullname"];
				$get_staff_email = $staff_details["email"];
				$staff_phone = $staff_details["phone"];
			}
			
			$searcharray = array('{{service_name}}','{{booking_date}}','{{business_logo}}','{{business_logo_alt}}','{{client_name}}','{{units}}','{{client_email}}','{{phone}}','{{payment_method}}','{{notes}}','{{contact_status}}','{{address}}','{{price}}','{{admin_name}}','{{firstname}}','{{lastname}}','{{app_remain_time}}','{{reject_status}}','{{company_name}}','{{booking_time}}','{{client_city}}','{{client_state}}','{{client_zip}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_address}}','{{admin_name}}','{{staff_name}}','{{staff_email}}');

			$replacearray = array($service_name, $booking_date , $business_logo, $business_logo_alt, $client_name, $units, $client_email, $client_phone, $payment_status, $client_notes, $client_status,$client_address,$price,$get_admin_name,$firstname,$lastname,'','',$admin_company_name,$booking_time,$client_city,$client_state,$client_zip,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_address,$get_admin_name,stripslashes($get_staff_name),stripslashes($get_staff_email));
			
			/* TEXTLOCAL CODE */
			if($setting->get_option('ld_sms_textlocal_status') == "Y")
			{
				if($setting->get_option('ld_sms_textlocal_send_sms_to_staff_status') == "Y"){
					if(isset($staff_phone) && !empty($staff_phone))
					{	
						$template = $objdashboard->gettemplate_sms("R",'S');
						$phone = $staff_phone;				
						if($template[4] == "E") {
							if($template[2] == ""){
								$message = base64_decode($template[3]);
							}
							else{
								$message = base64_decode($template[2]);
							}
						}
						$message = str_replace($searcharray,$replacearray,$message);
						$data = "username=".$textlocal_username."&hash=".$textlocal_hash_id."&message=".$message."&numbers=".$phone."&test=0";
						$ch = curl_init('http://api.textlocal.in/send/?');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$result = curl_exec($ch);
						curl_close($ch);
					}
				}
			}
				/*PLIVO CODE*/
				if($setting->get_option('ld_sms_plivo_status')=="Y"){
						if($setting->get_option('ld_sms_plivo_send_sms_to_staff_status') == "Y"){
							if(isset($staff_phone) && !empty($staff_phone))
							{ 
								$auth_id = $setting->get_option('ld_sms_plivo_account_SID');
								$auth_token = $setting->get_option('ld_sms_plivo_auth_token');
								$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');

								$template = $objdashboard->gettemplate_sms("R",'S');
								$phone = $staff_phone;
								if($template[4] == "E"){
										if($template[2] == ""){
												$message = base64_decode($template[3]);
										}
										else{
												$message = base64_decode($template[2]);
										}
										$client_sms_body = str_replace($searcharray,$replacearray,$message);
										/* MESSAGE SENDING CODE THROUGH PLIVO */
										$params = array(
												'src' => $plivo_sender_number,
												'dst' => $phone,
												'text' => $client_sms_body,
												'method' => 'POST'
										);
										$response = $p_client->send_message($params);
										/* MESSAGE SENDING CODE ENDED HERE*/
								}
							}
						}
				}
				if($setting->get_option('ld_sms_twilio_status') == "Y"){
						if($setting->get_option('ld_sms_twilio_send_sms_to_staff_status') == "Y"){
							if(isset($staff_phone) && !empty($staff_phone))
							{	
								$AccountSid = $setting->get_option('ld_sms_twilio_account_SID');
								$AuthToken =  $setting->get_option('ld_sms_twilio_auth_token'); 
								$twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

								$template = $objdashboard->gettemplate_sms("R",'S');
								$phone = $staff_phone;
								if($template[4] == "E") {
										if($template[2] == ""){
												$message = base64_decode($template[3]);
										}
										else{
												$message = base64_decode($template[2]);
										}
										$client_sms_body = str_replace($searcharray,$replacearray,$message);
										/*TWILIO CODE*/
										$message = $twilliosms_client->account->messages->create(array(
												"From" => $twilio_sender_number,
												"To" => $phone,
												"Body" => $client_sms_body));
								}
							}
						}
				}
			if($setting->get_option('ld_nexmo_status') == "Y"){
				if($setting->get_option('ld_sms_nexmo_send_sms_to_staff_status') == "Y"){
					if(isset($staff_phone) && !empty($staff_phone))
					{	
						$template = $objdashboard->gettemplate_sms("R",'S');
						$phone = $staff_phone;				
						if($template[4] == "E") {
							if($template[2] == ""){
								$message = base64_decode($template[3]);
							}
							else{
								$message = base64_decode($template[2]);
							}
							$ld_nexmo_text = str_replace($searcharray,$replacearray,$message);
							$res=$nexmo_client->send_nexmo_sms($phone,$ld_nexmo_text);
						}
					}
				}
			}
		}
	}	
    /*SMS SENDING CODE END*/
} 
elseif(isset($_POST['delete_booking'])){
    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
	$pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
	$gc_event_id = filter_var($_POST['gc_event_id'], FILTER_SANITIZE_STRING);
	$gc_staff_event_id = filter_var($_POST['gc_staff_event_id'], FILTER_SANITIZE_STRING);
	
	if($gc_hook->gc_purchase_status() == 'exist'){
		echo filter_var($gc_hook->gc_cancel_reject_booking_hook(), FILTER_SANITIZE_STRING);
	}
    $objdashboard->delete_booking($id);
}

if(isset($_POST['reschedual_booking_admin'])){
    $order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_STRING);
	$booking->order_id= filter_var($_POST['order_id'], FILTER_SANITIZE_STRING);
	$dd = $booking->readall_bookings_oid();
	$res_amt = $booking->read_net_amt();
	?>
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo filter_var($label_language_values['reschedule'], FILTER_SANITIZE_STRING); ?></h4>
			</div>
			<div class="modal-body">
				<h3><?php echo filter_var($label_language_values['reschedule'], FILTER_SANITIZE_STRING); ?></h3>
				<div class="col-xs-12">
					<div class="form-group">
						<label class="lda-col2 ld-w-50"><?php echo filter_var($label_language_values['amount'], FILTER_SANITIZE_STRING); ?>:</label>
						<div class="lda-col6">
							<input class="form-control" readonly="readonly" type="text" id="amt" value="<?php echo "$".$res_amt['net_amount'];?>"/>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="col-xs-12">
					<div class="form-group">
						<label class="lda-col2 ld-w-50"><?php echo filter_var($label_language_values['date_and_time'], FILTER_SANITIZE_STRING); ?>:</label>
						<div class="lda-col4 ld-w-50">
							<?php 
							$dates = date("Y-m-d",strtotime($dd['booking_pickup_date_time_start']));
							$slot_timess = date('H:i',strtotime($dd['booking_pickup_date_time_start']));
							$get_staff_id = $booking->get_staff_ids_from_bookings($dd['order_id']);	
							if($get_staff_id==""){
								$staff_id=1;
							}else{
								$staff_id_array = explode(",",$get_staff_id);
								$staff_id = $staff_id_array[0];
							}
							?>
							<input class="exp_cp_date form-control" id="expiry_date<?php  echo filter_var($dd['order_id'], FILTER_SANITIZE_STRING);?>" data-staffid="<?php echo filter_var($staff_id, FILTER_SANITIZE_STRING); ?>" value=	"<?php echo filter_var($dates, FILTER_SANITIZE_STRING);?>" data-date-format="yyyy/mm/dd" data-provide="datepicker" />
						</div>
						<div class="lda-col6 ld-w-50 float-right mytime_slots_booking">
							<?php 
							$t_zone_value = $setting->get_option('ld_timezone');
							$server_timezone = date_default_timezone_get();
							if(isset($t_zone_value) && $t_zone_value!=''){
								$offset= $first_step->get_timezone_offset($server_timezone,$t_zone_value);
								$timezonediff = $offset/3600;
							}else{
								$timezonediff =0;
							}
							if(is_numeric(strpos($timezonediff,'-'))){
								$timediffmis = str_replace('-','',$timezonediff)*60;
								$currDateTime_withTZ= strtotime("-".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
							}else{
								$timediffmis = str_replace('+','',$timezonediff)*60;
								$currDateTime_withTZ = strtotime("+".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
							}
							$select_time=date('Y-m-d',strtotime($dates));
							$start_date = date($select_time,$currDateTime_withTZ);
							$time_interval = $setting->get_option('ld_time_interval');
							$time_slots_schedule_type = $setting->get_option('ld_time_slots_schedule_type');
							$advance_bookingtime = $setting->get_option('ld_min_advance_booking_time');
							$ld_service_padding_time_before = $setting->get_option('ld_service_padding_time_before');
							$ld_service_padding_time_after = $setting->get_option('ld_service_padding_time_after');
							$booking_padding_time = $setting->get_option('ld_booking_padding_time');
							$time_schedule = $first_step->get_day_time_slot_by_provider_id($time_slots_schedule_type,$start_date,$time_interval,$advance_bookingtime,$ld_service_padding_time_before,$ld_service_padding_time_after,$timezonediff,$booking_padding_time,$staff_id);
							$allbreak_counter = 0;
							$allofftime_counter = 0;
							$slot_counter = 0;
							?>
							<select class="selectpicker mydatepicker_appointment   form-control" id="myuser_reschedule_time" data-size="10" style="" >
								<?php 
								if($time_schedule['off_day']!=true && isset($time_schedule['slots']) && sizeof($time_schedule['slots'])>0 && $allbreak_counter != sizeof($time_schedule['slots']) && $allofftime_counter != sizeof($time_schedule['slots'])){
									foreach($time_schedule['slots']  as $slot) {
										$ifbreak = 'N';
										/* Need to check if the appointment slot come under break time. */
										foreach($time_schedule['breaks'] as $daybreak) {
											if(strtotime($slot) >= strtotime($daybreak['break_start']) && strtotime($slot) < strtotime($daybreak['break_end'])) {
												$ifbreak = 'Y';
											}
										}
										/* if yes its break time then we will not show the time for booking  */
										if($ifbreak=='Y') { $allbreak_counter++; continue; }
										$ifofftime = 'N';
										foreach($time_schedule['offtimes'] as $offtime) {
											if(strtotime($dates.' '.$slot) >= strtotime($offtime['offtime_start']) && strtotime($dates.' '.$slot) < strtotime($offtime['offtime_end'])) {
												$ifofftime = 'Y';
											}
										}
										/* if yes its offtime time then we will not show the time for booking  */
										if($ifofftime=='Y') { $allofftime_counter++; continue; }
										$complete_time_slot = mktime(date('H',strtotime($slot)),date('i',strtotime($slot)),date('s',strtotime($slot)),date('n',strtotime($time_schedule['date'])),date('j',strtotime($time_schedule['date'])),date('Y',strtotime($time_schedule['date'])));
										if($setting->get_option('ld_hide_faded_already_booked_time_slots')=='on' && in_array($complete_time_slot,$time_schedule['booked'])) {
											continue;
										}
										if( in_array($complete_time_slot,$time_schedule['booked']) && ($setting->get_option('ld_allow_multiple_booking_for_same_timeslot_status')!='Y') ) { ?>
											<?php 
											if($setting->get_option('ld_hide_faded_already_booked_time_slots')=="on"){
												?>
												<option value="<?php echo date("H:i",strtotime($slot));?>" <?php  if(date("H:i",strtotime($slot)) == $slot_timess){ echo filter_var("selected", FILTER_SANITIZE_STRING);}?> class="time-slot br-2 ld-booked" >
													<?php 
													if($setting->get_option('ld_time_format')==24){
														echo date("H:i",strtotime($slot));
													}else{
														echo str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($slot)));
													}?>
												</option>
											<?php 
											}
											?>
										<?php 
										} else {
											if($setting->get_option('ld_time_format')==24){
												$slot_time = date("H:i",strtotime($slot));
											}else{
												$slot_time = str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($slot)));
											}
											?>
											<option value="<?php echo date("H:i",strtotime($slot));?>" <?php  if(date("H:i",strtotime($slot)) == $slot_timess){ echo filter_var("selected", FILTER_SANITIZE_STRING);}?> class="time-slot br-2 <?php  if(in_array($complete_time_slot,$time_schedule['booked'])){ echo' ld-booked';}else{ echo filter_var(' time_slotss', FILTER_SANITIZE_STRING); }?>" <?php  if(in_array($complete_time_slot,$time_schedule['booked'])){echo filter_var('', FILTER_SANITIZE_STRING); }else{ echo 'data-slot_date_to_display="'.date($getdateformate,strtotime($dates)).'" data-slot_date="'.$dates.'" data-slot_time="'.$slot_time.'"'; } ?>><?php if($setting->get_option('ld_time_format')==24){echo date("H:i",strtotime($slot));}else{echo str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($slot)));}?></option>
										<?php 
										} $slot_counter++;
									}
									if($allbreak_counter == sizeof($time_schedule['slots']) && sizeof($time_schedule['slots'])!=0){ ?>
										<option  class="time-slot"><?php echo filter_var($label_language_values['sorry_not_available'], FILTER_SANITIZE_STRING);?></option>
									<?php  }
								} else { ?>
									<option class="time-slot"><?php echo filter_var($label_language_values['sorry_not_available'], FILTER_SANITIZE_STRING);?></option>
								<?php  } ?>
							</select>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="col-xs-12">
					<div class="form-group">
						<label class="lda-col2 ld-w-50"><?php echo filter_var($label_language_values['notes'], FILTER_SANITIZE_STRING); ?>:</label>
						<div class="lda-col8">
							<textarea class="form-control" id="rs_notes" class="rs_notes"></textarea>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>	
			</div>
			<div class="modal-footer">
				<a href="javascript:void(0);" class="pull-left btn btn-info" id="edit_reschedual" data-gc_event="<?php echo filter_var($dd['gc_event_id'], FILTER_SANITIZE_STRING); ?>" data-gc_staff_event="<?php echo filter_var($dd['gc_staff_event_id'], FILTER_SANITIZE_STRING); ?>" data-pid="<?php echo filter_var($dd['staff_ids'], FILTER_SANITIZE_STRING); ?>" data-order="<?php echo filter_var($dd['order_id'], FILTER_SANITIZE_STRING);?>">Update appointment</a>
			</div>
		</div>
	</div>
	<?php
}
?>