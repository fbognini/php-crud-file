<?
	session_start();
  
	if(!isset($_SESSION['username']))
		$redirect = "login";
	else
		if($_SESSION['user-type'] == 'A')
			$redirect = "admin";
		else
			$redirect = "user";		
	
	header("location: views/".$redirect.".php");
?>