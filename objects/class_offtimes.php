<?php  

class laundry_offtimes{
		
		public $id;
        public $provider_id= 0;
        public $startdate;
        public $enddate;
		public $staff_id;
        public $table_name="ld_schedule_offtimes";
        public $conn;
		/*Function for Add offtimes*/
		public function add_offtimes(){
			$query="insert into `".$this->table_name."` (`id`,`provider_id`,`start_date_time`,`end_date_time`) values(NULL,'".$this->staff_id."','".$this->startdate."','".$this->enddate."')";
            $result=mysqli_query($this->conn,$query);
			$value=mysqli_insert_id($this->conn);
			return $value;
		}
        /*  function to get all off time which are added */
        public function get_all_offtimes($staff_id)
        {
            $query = "select * from `".$this->table_name."` where provider_id='".$staff_id."'";
            $result=mysqli_query($this->conn,$query);
            return $result;
        }
        /* function to delete the time slots */
        public function delete_offtimes(){
            $query = "delete from `".$this->table_name."` where `id`='".$this->id."'";
            $result=mysqli_query($this->conn,$query);
            return $result;
        }
}
?>