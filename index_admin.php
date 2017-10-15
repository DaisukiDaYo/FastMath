<?php
//allow only admin
if($_SESSION['regis']!='admin'){
		exit();	
}

echo "admin";
?>