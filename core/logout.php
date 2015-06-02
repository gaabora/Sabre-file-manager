<?php
	// Sabre logout script
	if (!$_SESSION) session_start();

	if(isSet($_GET['c']) and $_GET['c'] == "1"){
		$_SESSION = array();
		session_destroy();
		setcookie("sabre_cookie", "-", time()-31536000);
	}
	
	header('location: ../?logout=1');
?>
