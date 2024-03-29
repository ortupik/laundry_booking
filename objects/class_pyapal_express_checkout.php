<?php  

  class laundry_ld_paypal{
  
	 /* Object property post variables (NVP) name value pairs */
	 var $pv; 
	 
	 /* Object property paypal method name */
	 var $pp_method_name;
	 
	 /* Object property payment mode */
	 var $mode;
     
	 
	 /**
     * Paypal NVP API CALL 
     *
     * return array API results 
     */
	 public function paypal_nvp_api_call() {
					
			if(strtoupper($this->mode)=='SANDBOX') {
			  $this->mode = '.sandbox';
			}
			
			$api_url = "https://api-3t".$this->mode.".paypal.com/nvp";
	    
			$postvars = "METHOD=".$this->pp_method_name.$this->pv;						
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $api_url);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
			$curl_res = curl_exec($ch);
		
			if(!$curl_res) {
				exit("$this->pp_method_name failed: ".curl_error($ch).'('.curl_errno($ch).')');
			}
			$result_array = explode("&", $curl_res);
			$return_array = array();
			foreach ($result_array as $key => $value) {
				$tmp_array = explode("=", $value);
				if(sizeof($tmp_array) > 1) {
					$return_array[$tmp_array[0]] = $tmp_array[1];
				}
			}
			
			if((0 == sizeof($return_array)) || !array_key_exists('ACK', $return_array)) {
				exit("Invalid HTTP Response for POST request($postvars) to $api_url.");
			}
	 
			return $return_array;
	 
	 }
 
  }
?>