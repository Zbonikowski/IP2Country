<?
	if($_GET['step']==2){
		echo "<h2>Step 2</h2>";
		echo "If You got error 'Maximum execution time' error, please download file from http://googs.me/ip/GeoIPCountryWhois.csv and upload in step one.";
		if(!$_GET['file']){
			$file="http://googs.me/ip/GeoIPCountryWhois.csv";
		}else{
			$file=$_GET['file'];
		}
		if($f=file($file)){
			include('ip2c_config.php');
			if($db=mysql_connect($db_host, $db_login, $db_pass)){
				if(!mysql_select_db($db_database)){
					echo "Error - please create database $db_database";
				}else{
					mysql_query("CREATE TABLE `$db_table` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ip_start` INT NOT NULL ,
`ip_end` INT NOT NULL ,
`2l_country` VARCHAR( 2 ) NOT NULL ,
`3l_country` VARCHAR( 3 ) NOT NULL ,
`country` VARCHAR( 255 ) NOT NULL 
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;");
					for($i=0;$i<count($f);$i++){
						$d=explode(",",$f[$i]);
						mysql_query("INSERT INTO $db_table SET ip_start='".str_replace("\"","",$d[2])."',ip_end='".str_replace("\"","",$d[3])."',2l_country='".str_replace("\"","",$d[4])."',3l_country='".str_replace("\"","",$d[4])."',country='".str_replace("\"","",str_replace("\n","",$d[5]))."'");
					}
					echo "<br/><br/>install complete.";
					exit;
				}
			}else{
				echo "Error - database connect.";
			}
		}else{
			echo "Error - cannot download file http://googs.me/ip/GeoIPCountryWhois.csv, please try download http://googs.me/ip/ip_database.csv or write to kacper@googs.me or work@z15.pl.";
		}
	}
	if($_POST['submit']){
		$conf='
		/*
		
		IP 2 Country class on MySQL
		2011.01
		Kacper Zbonikowski - POLAND
		mobile +48 501079176 email: zbonikowski@o2.pl
		Free to non-comercial use
		
		
		*/
			$db_host="'.$_POST['host'].'";
			$db_login="'.$_POST['login'].'";
			$db_pass="'.$_POST['pass'].'";
			$db_database="'.$_POST['database'].'";
			$db_table="'.$_POST['table'].'";
';
		if($fp = fopen('ip2c_config.php', 'w')){
			fwrite($fp, '<?'.$conf.'?>');
			fclose($fp);
			if($_FILES['csv']['tmp_name'])header("Location: install.php?step=2&file=".$_FILES['csv']['tmp_name']);
			header("Location: install.php?step=2");
		}else{
			echo "Error, cannot write to file ip2c_config.php .<br/>\nPlease write this:<br/>\n<br/>\n<code>&lt;?".nl2br($conf)."?&gt;</code><br/>\n<br/>\nto ip2c_config.php and go to <a href='install.php?step=2'>install.php?step=2</a>";
		}
	}
?>
<h2>Step 1</h2>
<form action="#" method="POST" enctype="multipart/form-data">
	<fieldset>
		<legend>DB info:</legend>
		Database host: <input type="text" name="host"/><br/>
		Database login: <input type="text" name="login"/><br/>
		Database password: <input type="text" name="pass"/><br/>
		Database name:<input type="text" name="database"/><br/>
		Database table name:<input type="text" name="table"/><br/>
		File (if you download ip_database.csv)<input type="file" name="csv"/><br/>
		<input type="submit" name="submit"/>
	</fieldset>
</form>