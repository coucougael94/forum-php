<?php
	session_start();
	if(isset($_SESSION))
		$_SESSION = array();
	if(isset($_SESSION))
		unset($_SESSION);
	setcookie('pseudo', '', time()+100, null, null, false, true);
	setcookie('pass', '', time()+100, null, null, false, true);
	if(isset($_COOKIE))
		unset($_COOKIE);
	session_destroy();
	header('Location: index.php');
	//y0vH8JLhO1
	//contact@mstsor.esy.es est y0vH8JLhO1
	//pour le compte Nicolas : mdp :1A79ncdkdn9272HDJD
?>
