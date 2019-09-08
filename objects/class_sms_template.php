<?php  

class laundry_sms_template{
    public $id;
    public $sms_subject;
    public $sms_message;
    public $default_message;
    public $sms_template_status;
    public $sms_template_type;
    public $user_type;
    public $conn;
    public $ld_sms_templates="ld_sms_templates";
    /*
    * Function for Read All client_sms_template
    *
    */
    public function readall_client_sms_template(){
        $query="select * from `".$this->ld_sms_templates."` where `user_type` = 'C'";
        $result=mysqli_query($this->conn,$query);
        return $result;
    }
    /*
    * Function for Read All admin_sms_template
    *
    */
    public function readall_admin_sms_template(){
        $query="select * from `".$this->ld_sms_templates."` where `user_type` = 'A'";
        $result=mysqli_query($this->conn,$query);
        return $result;
    }
		/*
    * Function for Read All staff_sms_template
    *
    */
    public function readall_staff_sms_template(){
        $query="select * from `".$this->ld_sms_templates."` where `user_type` = 'S'";
        $result=mysqli_query($this->conn,$query);
        return $result;
    }
    /*
    * Function for Read one client_sms_template
    *
    */
    public function readone_client_sms_template(){
        $query="select * from `".$this->ld_sms_templates."` where `user_type` = 'C' and `sms_template_type` = '".$this->sms_template_type."'";
        $result=mysqli_query($this->conn,$query);
        return $result;
    }
    /*
    * Function for Read one admin_sms_template
    *
    */
    public function readone_admin_sms_template(){
        $query="select * from `".$this->ld_sms_templates."` where `user_type` = 'A' and `sms_template_type` = '".$this->sms_template_type."'";
        $result=mysqli_query($this->conn,$query);
        return $result;
    }
		/*
    * Function for Read one staff_sms_template
    *
    */
    public function readone_staff_sms_template(){
        $query="select * from `".$this->ld_sms_templates."` where `user_type` = 'S' and `sms_template_type` = '".$this->sms_template_type."'";
        $result=mysqli_query($this->conn,$query);
        return $result;
    }
    /*
    * Function for update sms_template
    *
    */
    public function update_sms_template(){
        $query="update `".$this->ld_sms_templates."` set `sms_message`='".$this->sms_message."' where `id` = ".$this->id;
        $result=mysqli_query($this->conn,$query);
        return $result;
    }
    /*
    * Function for update sms_template_status
    *
    */
    public function update_sms_template_status(){
        $query="update `".$this->ld_sms_templates."` set `sms_template_status`='".$this->sms_template_status."' where `id` = '".$this->id."'";
        $result=mysqli_query($this->conn,$query);
        return $result;
    }
    /*
	* Function for get_default_sms_template content
	*
	*/
    public function get_default_sms_template(){
        $query="select `default_message` from `".$this->ld_sms_templates."` where `id` = '".$this->id."'";
        $result=mysqli_query($this->conn,$query);
        $value=mysqli_fetch_array($result);
        return $value[0];
    }
}
?>