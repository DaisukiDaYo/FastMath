<?php
session_start();

$thislink=$_SERVER["PHP_SELF"]; // index.php

echo "<head><style>body{background-image: url(https://images3.alphacoders.com/581/thumb-1920-581876.png);background-position: center;background-repeat: no-repeat;background-attachment: fixed;background-color: #FFFFFF;}\n";
echo "</style>";
echo "</head>";

// make pass dir if not exists
if(!is_dir('pass')) {
	// keep register user and password
	`mkdir pass;chmod 777 pass`;
	`echo 'deny from all' > pass/.htaccess`;
}
if(!is_dir('online')) {
	// keep online status
	`mkdir online;chmod 777 online`;
	`echo 'deny from all' > online/.htaccess`;
}
if(!is_dir('result')) {
	// keep selection result
	`mkdir result;chmod 777 result`;
	`echo 'deny from all' > result/.htaccess`;
}

// admin if not exists
if(!file_exists('pass/admin.php')){
			if(isset($user)) unset($user);
			// add admin with pass=999
			$p=md5('999');
			$str="<?php\n\$fullname='G1 Fastmath';\n\$pass='$p';\n?>";
			file_put_contents('pass/admin.php',$str);
		}


// register 
if(isset($_REQUEST['name']) and isset($_REQUEST['pass']) and isset($_REQUEST['regis'])){
		if(isset($_SESSION['regis'])) unset($_SESSION['regis']);
		// take submitted data
		$n=trim($_REQUEST['name']);
		$fullname=trim($_REQUEST['fullname']);
		$p=trim($_REQUEST['pass']);
		if($n=='' or $p=='' or $fullname=='') {
			//if empty do nothing
			if(isset($_SESSION['regis'])) unset($_SESSION['regis']);
			if(isset($_REQUEST['name'])) unset($_REQUEST['name']);
			if(isset($_REQUEST['fullname'])) unset($_REQUEST['fullname']);
			if(isset($_REQUEST['pass'])) unset($_REQUEST['pass']);
		}else{
            $fname='pass/'.$n.'.php';
			if(!file_exists($fname)){
				// add new user
				$p=md5($p);
				$str="<?php\n\$fullname='$fullname';\n\$pass='$p';\n?>";
				file_put_contents($fname,$str);
				$_SESSION['regis']=$n;
				$_SESSION['fullname']=$fullname;
			}else{            
				include($fname);
				if($pass!=md5($p)){
					// not same pass
					echo "<font color=red>Name: $n exists.</font><br>";
					echo "If you try to register,choose a new name.<br>";
					echo "If you try to login,give new password.<br>";
					echo "<a href=\"$thislink\">Go back</a>";
					exit();
				}else{				
					$_SESSION['regis']=$n;
					$_SESSION['fullname']=$fullname;
				}
			}
		}
}

// login
if(isset($_REQUEST['name']) and isset($_REQUEST['pass']) and isset($_REQUEST['login'])){
		unset($_SESSION['regis'],$_SESSION['fullname']);
		if(isset($_SESSION['login'])) unset($_SESSION['login']);
		// take submitted data
		$n=trim($_REQUEST['name']);
		$p=trim($_REQUEST['pass']);
		if($n=='' or $p=='') {
			//if empty do nothing
			if(isset($_SESSION['regis'])) unset($_SESSION['regis']);
			if(isset($_REQUEST['name'])) unset($_REQUEST['name']);
			if(isset($_REQUEST['fullname'])) unset($_REQUEST['fullname']);
			if(isset($_REQUEST['pass'])) unset($_REQUEST['pass']);
		}else{
            $fname='pass/'.$n.'.php';
			if(!file_exists($fname)){
				if(isset($_SESSION['regis'])) unset($_SESSION['regis']);
				if(isset($_REQUEST['name'])) unset($_REQUEST['name']);
				if(isset($_REQUEST['fullname'])) unset($_REQUEST['fullname']);
				if(isset($_REQUEST['pass'])) unset($_REQUEST['pass']);
			}else{            
				include($fname);
				if($pass==''){
					echo "<font color=red>New password for userName: $n has set to $p</font><br>";
					$_SESSION['regis']=$n;
					$_SESSION['fullname']=$fullname;
					// write new pass to file
					$loginname=$n;
					$pass=md5($p);
					$str="<?php\n\$fullname='$fullname';\n\$pass='$pass';\n?>";
					file_put_contents($fname,$str);        

				}elseif($pass!=md5($p)){
					// not same pass
					echo "<font color=red>Name: $n exists.</font><br>";
					echo "If you try to register,choose a new name.<br>";
					echo "If you try to login,give new password.<br>";
					echo "<a href=\"$thislink\">Go back</a>";
					exit();
				}else{				
					$_SESSION['regis']=$n;
					$_SESSION['fullname']=$fullname;
				}
			}
		}
}

if(isset($_REQUEST['login_form'])){
	// gen login form
	echo "<center>\n";
	echo "<form action=\"$thislink\" method=\"post\">\n";
	echo "	<table width=300 bgcolor=white>\n";
	echo "		<tr><td colspan=2 align=center><font color=red size=5>คิดเลขเร็ว</font></td></tr>\n";
	echo "		<tr><td colspan=2 align=center>Login</td></tr>\n";
	echo "		<tr><td>User Name: </td><td><input type=\"text\" name=\"name\"></td></tr>\n";
	echo "		<tr><td>Password: </td><td><input type=\"password\" name=\"pass\"></td></tr>\n";
	echo "		<input type=\"hidden\" name=\"login\" value=\"y\">\n";
	echo "		<tr><td colspan=2 align=center><input type=\"submit\" name=\"submit\" value=\"Login\"></td></tr>\n";
	echo "	</table>";
	echo "</form>";
	echo "</center>\n";
	exit();
}elseif(!isset($_SESSION['regis'])){
		
	// gen registeration form
	echo "<center>\n";
	echo "<form action\$thislink\" method=\"post\">\n";
	echo "	<table width=300 bgcolor=white>\n";
	echo "		<tr><td colspan=2 align=center><font color=red size=5>คิดเลขเร็ว</font></td></tr>\n";
	echo "		<tr><td colspan=2 align=center>Register</td></tr>\n";
	echo "		<tr><td>User Name: </td><td><input type=\"text\" name=\"name\"></td></tr>\n";
	echo "		<tr><td>FullName: </td><td><input type=\"text\" name=\"fullname\"></td></tr>\n";
	echo "		<tr><td>Password: </td><td><input type=\"password\" name=\"pass\"></td></tr>\n";
	echo "		<input type=\"hidden\" name=\"regis\" value=\"y\">\n";
	echo "		<tr><td colspan=2 align=center><input type=\"submit\" name=\"submit\" value=\"Register\"></td></tr>\n";
	echo "	</table>";
	echo "</form>";

	echo "<form action=\"$thislink\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"login_form\" value=\"y\">\n";	
	echo "<input type=\"submit\" name=\"submit\" value=\"Login\"><br>\n";
	echo "</form>";
	echo "</center>\n";
	exit();

}

// logout
if(isset($_REQUEST['logout'])){
		unset($_SESSION['regis'],$_SESSION['fullname']);
		echo "<a href=\"$thislink\"><font color=white>Home</font></a>";
		exit();
}

if($_SESSION['regis']=='admin'){
	include('index_admin.php');
}else{
	include('index_user.php');
}

// logout button
echo "<form action=\"$thislink\" method=\"post\">\n";
echo "<input type=\"submit\" name=\"logout\" value=\"Logout\"><br>\n";
echo "</form>";
exit();

?>