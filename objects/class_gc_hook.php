<?php  
class laundry_gcHook{
	
	function __construct() {
		if (is_file(dirname(dirname(__FILE__)).'/extension/GoogleCalendar/GoogleCalendar.php')) 
		{
			include(dirname(dirname(__FILE__)).'/extension/GoogleCalendar/GoogleCalendar.php');
		}
	}
	public $conn;
	public function gc_purchase_status() {
		$query="select option_value from `ld_settings` where `option_name`='ld_gc_purchase_status'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		$file_path = dirname(dirname(__FILE__)).'/extension/GoogleCalendar/GoogleCalendar.php';
		if($value[0] == 'Y'){
			if(file_exists($file_path)) {
				return "exist";
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	public function gc_setting_menu_hook() {
		return gc_setting_menu();
	}
	public function gc_settings_menu_content_hook(){
		return gc_settings_menu_content();
	}
	public function gc_staff_settings_menu_content_hook(){
		return gc_staff_settings_menu_content();
	}
	public function gc_settings_save_js_hook() {
		return gc_settings_save_js();
	}
	public function gc_staff_settings_save_js_hook() {
		return gc_staff_settings_save_js();
	}
	public function gc_settings_save_ajax_hook() {
		return gc_settings_save_ajax();
	}
	public function gc_staff_settings_save_ajax_hook() {
		return gc_staff_settings_save_ajax();
	}
	public function gc_setting_configure_js_hook() {
		return gc_setting_configure_js();
	}
	public function gc_staff_setting_configure_js_hook() {
		return gc_staff_setting_configure_js();
	}
	public function gc_setting_configure_ajax_hook() {
		return gc_setting_configure_ajax();
	}
	public function gc_staff_setting_configure_ajax_hook() {
		return gc_staff_setting_configure_ajax();
	}
	public function gc_staff_setting_disconnect_js_hook() {
		return gc_staff_setting_disconnect_js();
	}
	public function gc_setting_disconnect_js_hook() {
		return gc_setting_disconnect_js();
	}
	public function gc_setting_disconnect_ajax_hook() {
		return gc_setting_disconnect_ajax();
	}
	public function gc_staff_setting_disconnect_ajax_hook() {
		return gc_staff_setting_disconnect_ajax();
	}
	public function gc_staff_setting_verify_js_hook() {
		return gc_staff_setting_verify_js();
	}
	public function gc_setting_verify_js_hook() {
		return gc_setting_verify_js();
	}
	public function gc_add_booking_ajax_hook() {
		return gc_add_booking_ajax();
	}
	public function gc_add_staff_booking_ajax_hook() {
		return gc_add_staff_booking_ajax();
	}
	public function gc_reschedule_booking_ajax_hook() {
		return gc_reschedule_booking_ajax();
	}
	public function gc_remove_sampledata_booking_hook() {
		return gc_remove_sampledata_booking_ajax();
	}
	public function gc_cancel_reject_booking_hook() {
		return gc_cancel_reject_booking_ajax();
	}
	public function google_cal_TwoSync_hook() {
		return google_cal_TwoSync_ajax();
	}
}
?>