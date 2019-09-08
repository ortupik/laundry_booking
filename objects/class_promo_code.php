<?php  

class laundry_promo_code {
	public $id;
	public $coupon_code;
	public $coupon_type;
	public $value;
	public $limit_use;
	public $expiry_date;
	public $coupon_service;
	public $title;
	public $service_id;
	public $service_title;
	public $conn;
	public $table_name="ld_coupons";
	public $table_name1="ld_services";
	
		public function add_promo_code(){
			$sql="insert into `".$this->table_name."` (`id`,`coupon_code`,`coupon_type`,`coupon_limit`,`coupon_used`,`coupon_value`,`coupon_expiry`,`status`) values(NULL,'".$this->coupon_code."','".$this->coupon_type."','".$this->limit_use."','0','".$this->value."','".$this->expiry_date."','on')";
			$result=mysqli_query($this->conn,$sql);
			return $result;
		}
		
		
		public function update_promo_code() {
			$sql="update `".$this->table_name."` set `coupon_code`='".$this->coupon_code."',`coupon_type`='".$this->coupon_type."',`coupon_value`='".$this->value."',`coupon_limit`='".$this->limit_use."',`coupon_expiry`='".$this->expiry_date."' where `id`='".$this->id."'";
			$result=mysqli_query($this->conn,$sql);
			return $result;
		
		}
		
		public function readall_service() {
			$query="select * from `".$this->table_name1."`";
			$result=mysqli_query($this->conn,$query);
			return $result;
		}
		
		
		public function readall(){
		$sql="select * from `".$this->table_name."`";
		$result=mysqli_query($this->conn,$sql);
		return $result;
		}
		
		public function delete_promo_code() {
			$sql="delete from `".$this->table_name."` where `id`='".$this->id."' ";
			$result=mysqli_query($this->conn,$sql);
   			return $result;
		}
		public function readone() {
			 $query="select * from `".$this->table_name."` where `id`='".$this->id."'";
			$result=mysqli_query($this->conn,$query);
			$value=mysqli_fetch_array($result);
			return $value;	
		
		}
    public function check_same_title(){
        $query = "select * from `".$this->table_name."` where `coupon_code`='".ucwords($this->coupon_code)."'";
        $result=mysqli_query($this->conn,$query);
        return $result;
    }
}
?>