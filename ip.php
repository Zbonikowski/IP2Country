<?
/*

IP 2 Country class on MySQL
2011.01
Kacper Zbonikowski - POLAND
mobile +48 501079176 email: zbonikowski@o2.pl
Free to non-comercial use


if you use ip_database.csv please add http://webnet77.com/ link on your site


*/
	include "ipclass.php";
	$ip=new get_user_info;
	$ip->user_info();
	$ip->show_all_info();
	
	$ip->set_ip("83.123.43.53");
	$ip->dist_ip();
	$ip->count_ip();
	$ip->search_country();
			
	$ip->_debug();
?>