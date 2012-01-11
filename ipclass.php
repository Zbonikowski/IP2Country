<?
/*

IP 2 Country class on MySQL
2011.01
Kacper Zbonikowski - POLAND
mobile +48 501079176 email: zbonikowski@o2.pl
Free to non-comercial use


if you use ip_database.csv please add http://webnet77.com/ link on your site


*/
	class get_user_info{
		private $ip="";
		private $ex_ip;
		private $error;
		private $cip="";
		private $ips;
		private $user_info;
		private $db;
		private $db_host;
		private $db_login;
		private $db_pass;
		private $db_database;
		private $db_table;
		function get_user_info(){
			$this->get_user_ip();
		}
		function user_info(){
			$this->get_user_ip();
			$this->dist_ip();
			$this->count_ip();
			if(!$this->error){
				$this->inc_db_ips();
				$this->search_country();
			}
		}
		function count_ip(){
			if(!$this->error)
				$this->cip=$this->ex_ip[3]+($this->ex_ip[2]*256)+($this->ex_ip[1]*256*256)+($this->ex_ip[0]*256*256*256);
		}
		function search_country(){
			if(!$this->error){
				$r=mysql_query("SELECT * FROM {$this->db_table} WHERE ip_start<=$this->cip AND ip_end>=$this->cip",$this->db) or die(mysql_error());
				if ($row = mysql_fetch_assoc($r)) {
					$this->user_info["user_ip"]=$this->ip;
					$this->user_info["user_cip"]=$this->cip;
					$this->user_info["full_country"]=$row['country'];
					$this->user_info["3letters_country"]=$row['3l_country'];
					$this->user_info["2letters_country"]=$row['2l_country'];
				}
			}
		}
		function inc_db_ips(){
			include_once('ip2c_config.php');
			$this->db_host=$db_host;
			$this->db_login=$db_login;
			$this->db_pass=$db_pass;
			$this->db_database=$db_database;
			$this->db_table=$db_table;
			if($this->db=mysql_connect($this->db_host, $this->db_login, $this->db_pass)){
				if(!mysql_select_db($this->db_database)){
					echo "Error select db";
				}
			}
		}
		function dist_ip(){
			if($this->ip!=''){
				$this->ex_ip=explode(".",$this->ip);
				if(count($this->ex_ip)<4)$this->error[]='bad IP dist_ip';
			}else{
				$this->error[]='no IP - dist_ip';	
			}
		}
		function get_user_ip(){
			$this->ip=$_SERVER[REMOTE_ADDR];
		}
		function set_ip($ip){
			if($ip!=''){
				$this->ip=$ip;
			}else{
				$this->error[]='no IP - set_ip';	
			}
		}
		function show_all_info(){
			if($this->error){
				echo $this->ip;
				echo "Bad IP";
				return;
			}
			echo $this->cip;
			echo " ";
			echo $this->ip;
			echo "<pre>";
			print_r($this->user_info);
			echo "</pre>";
		}
		function _debug(){
			$this->show_all_info();
			echo "<pre>";
			print_r($this->error);
			echo "</pre>";
		}
	}
?>