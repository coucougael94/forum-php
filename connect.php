<?php
	if (session_status() == PHP_SESSION_NONE)
	    session_start();
	function connect()
{
	$bdd =new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1');
	if(isset($_SESSION['pseudo'])AND isset($_SESSION['pass'])AND !empty($_SESSION['pseudo'])AND !empty($_SESSION['pass']))
	{
		if($estinscrit_req = $bdd->prepare("SELECT pseudo FROM user WHERE pseudo=:user_pseudo AND pass=:user_pass")){
		if (!$estinscrit_req->bindParam(":user_pseudo", $_COOKIE['pseudo'])) {
		    echo "Echec lors du liage des paramètres : (" . $estinscrit_req->errno . ") " . $estinscrit_req->error;
		}
		if (!$estinscrit_req->bindParam(":user_pass",$_COOKIE['pass'])) {
		    echo "Echec lors du liage des paramètres : (" . $estinscrit_req->errno . ") " . $estinscrit_req->error;
		}
		if (!$estinscrit_req->execute()) {
		    echo "Echec lors de l'exécution de la requête : (" . $estinscrit_req->errno . ") " . $estinscrit_req->error;
		}
		//$estinscrit_req2->execute();
		//$estinscrit_req->execute(array($_SESSION['pseudo'],$_SESSION['pass']));
		$estinscrit=$estinscrit_req->fetch();
		if($estinscrit['pseudo']!="")
		{
			setcookie('pseudo', $_SESSION['pseudo'], time() + 365*24*3600, null, null, false, true);
			setcookie('pass', $_SESSION['pass'], time() + 365*24*3600, null, null, false, true);
			return true;
		}}
		else {
		    printf("Errormessage: %s\n", $bdd->error);
		}
	}
	if(isset($_COOKIE['pseudo'])AND isset($_COOKIE['pass'])AND !empty($_COOKIE['pseudo'])AND !empty($_COOKIE['pass']))
	{
		if($estinscrit_req2 = $bdd->prepare("SELECT pseudo FROM user WHERE pseudo=:user_pseudo AND pass=:user_pass")){
		if (!$estinscrit_req2->bindParam(":user_pseudo", $_COOKIE['pseudo'])) {
		    echo "Echec lors du liage des paramètres : (" . $estinscrit_req2->errno . ") " . $estinscrit_req2->error;
		}
		if (!$estinscrit_req2->bindParam(":user_pass",$_COOKIE['pass'])) {
		    echo "Echec lors du liage des paramètres : (" . $estinscrit_req2->errno . ") " . $estinscrit_req2->error;
		}
		if (!$estinscrit_req2->execute()) {
		    echo "Echec lors de l'exécution de la requête : (" . $estinscrit_req2->errno . ") " . $estinscrit_req2->error;
		}
		//$estinscrit_req2->execute();
		//$estinscrit_req2->execute(array($_COOKIE['pseudo'],$_COOKIE['pass']));
		$estinscrit2=$estinscrit_req2->fetch();
		if($estinscrit2['pseudo']!="")
		{
			$_SESSION['pseudo']=$_COOKIE['pseudo'];
			$_SESSION['pass']=$_COOKIE['pass'];
			return true;
		}}
		else {
		    printf("Errormessage: %s\n", $bdd->error);
		}
	}
	return false;
} ?>