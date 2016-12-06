<?php
require("connect.php");
	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 
	include("hair.php");
	include("header.php");
	if(isset($_GET['commentaires'])AND htmlspecialchars($_GET['commentaires'])=='false'AND isset($_POST['wysibb'])AND isset($_POST['page_title']))
{
	if(connect())
	{
		$id_user=$bdd->prepare("SELECT id FROM user WHERE pseudo=? AND pass=?");
		$id_user->execute(array($_SESSION['pseudo'], $_SESSION['pass']));
		$user=$id_user->fetch();
		$fiches= $bdd->prepare("INSERT INTO fiches(id_createur,titre,contenue) VALUES(?,?,?)");
		$fiches->execute(array($user['id'],htmlspecialchars($_POST['page_title']),htmlspecialchars($_POST['wysibb'])));
		$fiches_id= $bdd->prepare("SELECT id FROM fiches ORDER BY id DESC LIMIT 0,1");
		$fiches_fetch=$fiches_id->fetch();
		header('Location: fiches.php?id='.$fiches_fetch['id']);
	}
}
else if(isset($_GET['fiche'])AND isset($_GET['commentaires'])AND htmlspecialchars($_GET['commentaires'])=='true')
{
	if(connect())
	{
		$id_user=$bdd->prepare("SELECT id FROM user WHERE pseudo=? AND pass=?");
		$id_user->execute(array($_SESSION['pseudo'], $_SESSION['pass']));
		$user=$id_user->fetch();
		$commentaires=$bdd->prepare("INSERT INTO commentaires_fiches(id_user,id_fiche,commentaires,date_post) VALUES(?,?,?,NOW())");
		$commentaires->execute(array($user['id'],intval(htmlspecialchars($_GET['fiche'])),htmlspecialchars($_POST['wysibb'])));
		header('Location: fiches.php?id='.htmlspecialchars($_GET['fiche']));
	}
}
?>
