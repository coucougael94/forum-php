<?php
	require("connect.php");
//titre_topic textarea
	require_once("header.php");
if(isset($_GET['commentaires'])AND htmlspecialchars($_GET['commentaires'])=="false"
	AND isset($_POST['wysibb'])AND isset($_POST['titre_topic']))
{
	if(strlen($_POST['titre_topic'])<150)
	{
	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 
	if(connect()){
		$id_user=$bdd->prepare("SELECT id FROM user WHERE pseudo=? AND pass=?");
		$id_user->execute(array($_SESSION['pseudo'], $_SESSION['pass']));
		$user=$id_user->fetch();
		$inscription_req=$bdd->prepare("INSERT INTO forum(id_createur,titre,open) VALUES (?,?,'ouvert')");
		$inscription_req->execute(array($user['id'], htmlspecialchars($_POST['titre_topic'])));
		$topic = $bdd->query("SELECT id FROM forum ORDER BY id DESC LIMIT 0,1");
		$topic_new=$topic->fetch();
		creatMessage(htmlspecialchars($_POST['wysibb']),$topic_new['id']);
	header('Location: forum.php?viewtopic='.htmlspecialchars($topic_new['id']));
	}
	else
		echo 'Vous n\'arriverai pas a créer de topic de cet manière. Veuillez vous connécter.';
	}
	else
		echo '<p>Le titre de votre topic ne doit pas excédé plus de 10000 caractères.</p>';
}
else if(isset($_GET['commentaires'])AND htmlspecialchars($_GET['commentaires'])=="true"AND isset($_GET['topic'])AND isset($_POST['wysibb']))
{
	if(connect())
		creatMessage($_POST['wysibb'],$_GET['topic']);
	else
		echo 'Vous n\'arriverai pas a créer de topic de cet manière. Veuillez vous connécter.';
	header('Location: forum.php?viewtopic='.htmlspecialchars($_GET['topic']));
}
function creatMessage($message,$topic_id)
{
	if(strlen($message)<10000)
	{
	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 
	$id_user=$bdd->prepare("SELECT id FROM user WHERE pseudo=? AND pass=?");
	$id_user->execute(array($_SESSION['pseudo'], $_SESSION['pass']));
	$user=$id_user->fetch();
	$message_req=$bdd->prepare("INSERT INTO message_forum(id_user,id_topic,message,date_post) VALUES(?,?,?,NOW())");
	$message_req->execute(array($user['id'],$topic_id,htmlspecialchars($message)));
	}
	else
		echo '<p>Vous ne pouvez pas créer de message ayant plus de 10000 caractères.</p>';
}
?>
