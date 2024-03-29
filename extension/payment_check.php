<?php
global $conn;
$payment_extensions_qry="select option_value from `ld_settings` where `option_name`='ld_payment_extensions'";
$payment_extensions_runqry=mysqli_query($conn,$payment_extensions_qry);
$result=mysqli_fetch_row($payment_extensions_runqry);
$final_result = unserialize($result[0]);
if(isset($final_result) && $final_result !== false){
	foreach($final_result as $key=>$value){
		$ext_path = $value['include_path'];
		if($ext_path != ''){
			if(is_file(dirname(dirname(__FILE__)).$ext_path)){
				include(dirname(dirname(__FILE__)).$ext_path);
			}
		}
	}
}
function payment_setting($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_setting();
	}else if($hook_name == 'ld_eway_purchase_status'){
		return eway_setting();
	}
}
function payment_settings_save_js($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_settings_save_js();
	}
	if($hook_name == 'ld_eway_purchase_status'){
		return eway_settings_save_js();
	}
}
function payment_currency_check_js($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_aud_currency_check_js();
	}
}
function payment_validation_js($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_validation_js();
	}
	if($hook_name == 'ld_eway_purchase_status'){
		return eway_validation_js();
	}
}
function payment_settings_save_ajax($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_settings_save_ajax();
	}
	if($hook_name == 'ld_eway_purchase_status'){
		return eway_settings_save_ajax();
	}
}
function payment_payment_selection($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_payment_selection();
	}
	if($hook_name == 'ld_eway_purchase_status'){
		return eway_payment_selection();
	}
}
function payment_display_cardbox_condition($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_display_cardbox_condition();
	}
	if($hook_name == 'ld_eway_purchase_status'){
		return eway_display_cardbox_condition();
	}
}
function payment_partial_deposit_toggle_condition($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_partial_deposit_toggle_condition();
	}
	if($hook_name == 'ld_eway_purchase_status'){
		return eway_partial_deposit_toggle_condition();
	}
}
function payment_checkout($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_checkout();
	}
	if($hook_name == 'ld_eway_purchase_status'){
		return eway_checkout();
	}
}
function payment_process_js($hook_name){
	if($hook_name == 'ld_payway_purchase_status'){
		return payway_process_js();
	}
	if($hook_name == 'ld_eway_purchase_status'){
		return eway_process_js();
	}
}