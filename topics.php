<?php
	require("connect.php");
	include_once("hair.php");
	$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=u491653048_db','u491653048_db','y0vH8JLhO1'); 
?>
<title>liste des forums</title>
<style type="text/css">
	table{border-collapse: collapse;margin-top: 10px;}
	th,td{border: 2px solid black; padding: 5px;}
	th{background-color: blue;}
	label,input{height: 25px; font-size: 20px;}
	form{margin-top: 10px;}
</style>
<?php include("header.php"); ?>
<form method="GET" action="topics.php">
<label for="search_topic">Rechercher un topic : </label><input type="search" name="search" id="search_topic"><input type="submit" style="height: 27px; background-color: #88AA88;" value="Rechercher !"></form>
<table><tr><th>Titre du topic</th><th>Créateur</th><th>Ouvert</th><th>Dernier message par et le</th></tr><?php
if(!isset($_GET['page']))
{
	if(isset($_GET['search']))
	{
		$forum_req=$bdd->query("SELECT titre,open,id_createur,id FROM forum WHERE titre LIKE '%".htmlspecialchars($_GET['search'])."%' ORDER BY id DESC LIMIT 0,10");
	}
	else
		$forum_req=$bdd->query("SELECT titre,open,id_createur,id FROM forum ORDER BY id DESC LIMIT 0,10");
}
else
{
	if(isset($_GET['search']))
	{
		$forum_req=$bdd->prepare("SELECT titre,open,id_createur,id FROM forum WHERE titre LIKE '%".htmlspecialchars($_GET['search'])."%' ORDER BY id DESC LIMIT ?,?");
		$forum_req->execute(array(intval(htmlspecialchars($_GET['page'])),intval(htmlspecialchars($_GET['page']))+10));
	}
	else
		$forum_req=$bdd->query("SELECT titre,open,id_createur,id FROM forum ORDER BY id DESC LIMIT 0,10");
}
$prochainepage="";//on prepare la chaine qui va affiché un lien vers la prochaine page
$nbPageaffiche=0;
	for($i=0;$forum=$forum_req->fetch();$i++)
	{

		$user_req=$bdd->prepare('SELECT pseudo FROM user WHERE id=?');
		$user_req->execute(array($forum['id_createur']));
		$user=$user_req->fetch();

		$message_forum_req=$bdd->prepare('SELECT id_user, date_post FROM message_forum WHERE id_topic=? ORDER BY date_post DESC LIMIT 0,1');
		$message_forum_req->execute(array($forum['id']));
		$message_forum=$message_forum_req->fetch();

		$user_dernier_post_req=$bdd->prepare('SELECT pseudo FROM user WHERE id=?');
		$user_dernier_post_req->execute(array($message_forum['id_user']));
		$user_dernier_post=$user_dernier_post_req->fetch();

		$datemsg = new DateTime($message_forum['date_post']);//conversion

		echo '<tr><td><a href="forum.php?viewtopic='.$forum['id'].'">'.$forum['titre'].'</a></td><td>'.$user['pseudo'].'</td><td>'.$forum['open'].'</td><td>'.$user_dernier_post['pseudo'].' - Le: '.date_format($datemsg, 'd/m/Y à H:i:s');
		echo '</td></tr>';
		if($i>10)
		{
			$pagetmp=intval(htmlspecialchars($_GET['page']));
			$pagetmp=$pagetmp+1;//ou $page_tmp++
			if(!isset($_GET['page']))
				$prochainepage='<a href="topics.php?page=2">Page suivante</a>';
			else
				$prochainepage='<a href="topics.php?page='.$pagetmp.'">Page suivante</a>';
			break;
		}
		$nbPageaffiche++;
	}
echo '</table>';
echo $prochainepage;
if($nbPageaffiche<9)
	echo '<p>Il n\'y as plus de résultat a afficher. Vous pouvez créer votre topic <a href="forum.php?createtopic=true">ici</a>.</p>';
	include_once("footer.php");

?>