<?php  
include(dirname(dirname(dirname(__FILE__)))."/config.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_setting.php");
include(dirname(dirname(dirname(__FILE__)))."/header.php");
include(dirname(dirname(dirname(__FILE__))).'/objects/class_staff_commision.php');

$con = new laundry_db();
$conn = $con->connect();

$settings = new laundry_setting();
$settings->conn = $conn;

$staff_commision = new laundry_staff_commision();
$staff_commision->conn=$conn;

$ld_currency_symbol=$settings->get_option('ld_currency_symbol');
$getdateformat=$settings->get_option('ld_date_picker_date_format');
$time_format = $settings->get_option('ld_time_format');

$lang = $settings->get_option("ld_language");
$label_language_values = array();
$language_label_arr = $settings->get_all_labelsbyid($lang);

if ($language_label_arr[1] != "" || $language_label_arr[3] != "" || $language_label_arr[4] != "" || $language_label_arr[5] != "")
{
	$default_language_arr = $settings->get_all_labelsbyid("en");
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
    $default_language_arr = $settings->get_all_labelsbyid("en");
    
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

include(dirname(dirname(dirname(__FILE__))).'/assets/lib/date_translate_array.php');
if(isset($_POST['staff_payment_details'])){
	$all = $staff_commision->get_staff_detail(filter_var($_POST['staff_ids'], FILTER_SANITIZE_STRING));
	$i = 1;
	if(mysqli_num_rows($all)>0){
		while($a = mysqli_fetch_array($all)){
		?>
			<tr>
				<td><?php echo filter_var($i, FILTER_SANITIZE_STRING); ?><input type="hidden" class="staff_pymnt_id" value="<?php echo filter_var($a['id'], FILTER_SANITIZE_STRING); ?>" /><input type="hidden" class="staff_pymnt_orderid" value="<?php echo filter_var($_POST['order_id'], FILTER_SANITIZE_STRING); ?>" /></td>
				<td><?php echo filter_var($a['fullname'], FILTER_SANITIZE_STRING); ?></td>
				<td>
					<div class="input-group">
						<span class="input-group-addon"><?php echo filter_var($ld_currency_symbol, FILTER_SANITIZE_STRING); ?></span>
						<input type="text" class="form-control sp_staff_amount" value="0" id="sp_staff_amount<?php  echo filter_var($a['id'], FILTER_SANITIZE_STRING); ?>" data-id="<?php echo filter_var($a['id'], FILTER_SANITIZE_STRING); ?>" placeholder="00">
					</div>
				</td>
				<td>
					<div class="input-group">
						<span class="input-group-addon"><?php echo filter_var($ld_currency_symbol, FILTER_SANITIZE_STRING); ?></span>
						<input type="text" class="form-control sp_staff_advance_paid" value="0" id="sp_staff_advance_paid<?php  echo filter_var($a['id'], FILTER_SANITIZE_STRING); ?>" data-id="<?php echo filter_var($a['id'], FILTER_SANITIZE_STRING); ?>" placeholder="00">
					</div>
				</td>
				<td>
					<div class="input-group">
						<span><?php echo filter_var($ld_currency_symbol, FILTER_SANITIZE_STRING); ?><span class="sp_staff_net_total<?php  echo filter_var($a['id'], FILTER_SANITIZE_STRING); ?>">0</span></span>
						<input type="hidden" class="form-control sp_staff_net_total" value="0" id="sp_staff_net_total<?php  echo filter_var($a['id'], FILTER_SANITIZE_STRING); ?>" data-id="<?php echo filter_var($a['id'], FILTER_SANITIZE_STRING); ?>" placeholder="00">
					</div>
				</td>
			</tr>
		<?php 
			$i++;
		}
	}else{
		?>
		<tr><td align="center" colspan='5'>Loading...</td></tr>
		<?php 
	}
}
if(isset($_POST['staff_payment_save'])){
	$data_staff_pymnt_id = explode(',',filter_var($_POST['staff_pymnt_id'], FILTER_SANITIZE_STRING));
	$data_sp_staff_amount = explode(',',filter_var($_POST['sp_staff_amount'], FILTER_SANITIZE_STRING));
	$data_sp_staff_advance_paid = explode(',',filter_var($_POST['sp_staff_advance_paid'], FILTER_SANITIZE_STRING));
	$data_sp_staff_net_total = explode(',',filter_var($_POST['sp_staff_net_total'], FILTER_SANITIZE_STRING));
	$order_id = filter_var($_POST['staff_pymnt_orderid'], FILTER_SANITIZE_STRING);
	
	$staff_commision->order_id=$order_id;
	$get_order_net_total = $staff_commision->get_total_payment();
	
	$get_pay_comission_total = 0;
	for($i=0;$i<sizeof($data_staff_pymnt_id);$i++){
		$get_pay_comission_total += (float)$data_sp_staff_net_total[$i];
		$staff_id = $data_staff_pymnt_id[$i];
		$get_pay_comission_total += (float)$staff_commision->get_booking_nettotal($staff_id,$order_id);
	}
	
	if($get_order_net_total < $get_pay_comission_total){
		die("not_ok_comission");
	}
	
	for($i=0;$i<sizeof($data_staff_pymnt_id);$i++){
		$staff_id = $data_staff_pymnt_id[$i];
		$amt_payable = $data_sp_staff_amount[$i];
		$advance_paid = $data_sp_staff_advance_paid[$i];
		$net_total = $data_sp_staff_net_total[$i];
		$payment_method = 'cash';
		$transaction_id = '';
		$payment_date = date('Y-m-d');
		
		$chk_existing = $staff_commision->check_staff_commision_payment($order_id,$staff_id);		
		if($chk_existing == 0){
			$staff_commision->insert_staff_commision($order_id,$staff_id,$amt_payable,$advance_paid,$net_total,$payment_method,$transaction_id,$payment_date);
		}else{
			$staff_commision->insert_staff_commision($order_id,$staff_id,$amt_payable,$advance_paid,$net_total,$payment_method,$transaction_id,$payment_date);
		}
		
	}
}