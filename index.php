<?
	session_start();
  
	if(!isset($_SESSION['username']))
		$redirect = "login";
	else
		if($_SESSION['usertype'] == 'A')
			$redirect = "admin";
		else
			$redirect = "user";		
	
	header("location: ".$redirect."/");
?>