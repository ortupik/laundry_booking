<?php  

class laundry_offbreaks{
		
		public $id;
        public $provider_id = 0;
        public $week_id;
        public $weekday_id;
        public $break_start;
        public $break_end;
		public $staff_id;
        public $table_name="ld_schedule_breaks";
        public $conn;
        /* insert new off break */
        public function insert_offbreaks(){
            $query="insert into `".$this->table_name."` (`id`,`provider_id`,`week_id`,`weekday_id`,`break_start`,`break_end`) values(NULL,'".$this->staff_id."','".$this->week_id."','".$this->weekday_id."','".$this->break_start."','".$this->break_end."')";
            $result=mysqli_query($this->conn,$query);
            $value=mysqli_insert_id($this->conn);
            return $value;
        }
        /* get one record of the last inserted id */
        public function getlastidrecord($id){
            $query = "select * from `".$this->table_name."` where `id`=".$id;
            $result=mysqli_query($this->conn,$query);
            $value=mysqli_fetch_row($result);
            return $value;
        }
        /* update start time */
        public function update_starttime(){
            $query="update `".$this->table_name."` set `week_id`='".$this->week_id."',`weekday_id`='".$this->weekday_id."', `break_start`='".$this->break_start."' where `id`='".$this->id."'";
            $result=mysqli_query($this->conn,$query);
            return $result;
        }
        /* update end time */
        public function update_endtime(){
			$query="update `".$this->table_name."` set `week_id`='".$this->week_id."',`weekday_id`='".$this->weekday_id."', `break_end`='".$this->break_end."' where `id`='".$this->id."'";
			$result=mysqli_query($this->conn,$query);
			return $result;
        }
        /* delete the off breaks */
        public function delete_off_breaks(){
            $query = "delete from `".$this->table_name."` where `id` = '".$this->id."'";
            $result=mysqli_query($this->conn,$query);
            return $result;
        }
        /* get breaks by week_id and weekday_id */
        public function getdataby_week_day_id($staff_id)
        {
            $query = "select * from `".$this->table_name."` where `week_id`=".$this->week_id." and `weekday_id` = '".$this->weekday_id."' and provider_id = '".$staff_id."'";
            $result=mysqli_query($this->conn,$query);
            return $result;
        }
}
?>