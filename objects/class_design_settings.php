<?php  

class laundry_design_settings{
	public $id;
	public $title;
	public $design;
	public $tablename="ld_setting_design";
	public $conn;
	/*Function for Read Only one data matched with Id*/
	public function readone(){
		$query="select * from `".$this->tablename."` where `title`='".$this->title."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
	}
	/*Function for Add setting_design*/
	public function add_setting_design(){
		$query="insert into `".$this->tablename."` values(NULL,'".$this->title."','".$this->design."')";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_insert_id($this->conn);
		return $value;
	}
	/*Function for Update  in this*/
	public function update_setting_design(){
		$query="update `".$this->tablename."` set `design`='".$this->design."' where `title`='".$this->title."' ";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
?>